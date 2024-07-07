<?php
add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/movies/(?P<page>\d+)', array(
        'methods' => 'GET',
        'callback' => 'get_movies_via_rest',
        'args' => array(
            'page' => array(
                'validate_callback' => function ($param, $request, $key) {
                    return is_numeric($param);
                }
            ),
        ),
    ));
});
function fetch_movies_from_api($page = 1, $keyword = '') {
    // Define a unique cache key based on endpoint and parameters
    $cache_key = 'movies_api_' . md5($page . '_' . $keyword);

    // Try to fetch data from cache
    $cached_data = get_transient($cache_key);
    if ($cached_data) {
        return $cached_data;
    }

    // If not cached, fetch data from API
    $api_key = '79b8ee4ce294df252bfbb388ae6472b9';
    $base_url = 'https://api.themoviedb.org/3/';
    $endpoint = $keyword ? "search/movie?query={$keyword}&page={$page}" : "movie/popular?page={$page}";
    $url = "{$base_url}{$endpoint}&api_key={$api_key}";

    // Set timeout and user-agent for the request
    $args = array(
        'timeout' => 30,  // Adjust timeout as needed
        'headers' => array(
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
        ),
    );

    // Fetch data from API
    $response = wp_remote_get($url, $args);

    // Check for WP_Error
    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        error_log('API Request Error: ' . $error_message);
        return ['error' => 'API Request Error: ' . $error_message];
    }

    // Retrieve response body
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    // Check for API errors
    if (isset($data['errors'])) {
        error_log('API Error: ' . implode(', ', $data['errors']));
        return ['error' => 'API Error: ' . implode(', ', $data['errors'])];
    }

    // Cache data for 1 hour (3600 seconds)
    set_transient($cache_key, $data, 3600);

    return [
        'results' => isset($data['results']) ? $data['results'] : [],
        'total_pages' => isset($data['total_pages']) ? $data['total_pages'] : 0,
    ];
}

function get_movie_imdb_id($movie_id) {
    // Check cache for IMDb ID to avoid unnecessary API requests
    $cache_key = 'imdb_id_' . $movie_id;
    $cached_imdb_id = get_transient($cache_key);
    if ($cached_imdb_id) {
        return $cached_imdb_id;
    }

    // Fetch IMDb ID from API if not cached
    $api_key = '79b8ee4ce294df252bfbb388ae6472b9';
    $url = "https://api.themoviedb.org/3/movie/{$movie_id}?api_key={$api_key}";

    $response = wp_remote_get($url);
    if (is_wp_error($response)) {
        error_log('Error fetching IMDb ID for movie ' . $movie_id . ': ' . $response->get_error_message());
        return null;
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (isset($data['errors'])) {
        error_log('API Error: ' . implode(', ', $data['errors']));
        return null;
    }

    // Cache IMDb ID for 24 hours (86400 seconds)
    $imdb_id = $data['imdb_id'] ?? null;
    if ($imdb_id) {
        set_transient($cache_key, $imdb_id, 86400); // Cache IMDb ID for 24 hours
    }

    return $imdb_id;
}

function get_movies_via_rest($data) {
    $page = isset($data['page']) ? intval($data['page']) : 1;
    $keyword = isset($_GET['keyword']) ? sanitize_text_field($_GET['keyword']) : '';

    // Validate page parameter
    if ($page < 1) {
        return rest_ensure_response(['error' => 'Invalid page number']);
    }

    // Fetch movies from API or database using your existing function
    $api_response = fetch_movies_from_api($page, $keyword);

    return rest_ensure_response($api_response);
}

add_action('wp_ajax_fetch_movies', 'fetch_movies');
add_action('wp_ajax_nopriv_fetch_movies', 'fetch_movies');

function fetch_movies() {
    // Check nonce for security
    check_ajax_referer('fetch_movies_nonce', 'nonce');

    $page = isset($_GET['paged']) ? intval($_GET['paged']) : 1;
    $keyword = isset($_GET['keyword']) ? sanitize_text_field($_GET['keyword']) : '';

    $data = fetch_movies_from_api($page, $keyword);

    if ($data) {
        wp_send_json_success($data);
    } else {
        wp_send_json_error('No movies found.');
    }
}
?>
