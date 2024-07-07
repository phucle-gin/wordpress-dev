<?php
/* Template Name: Movie Listing */
get_header(); 
?>
<section class="page-header overlay-gradient" style="background: url(<?php echo esc_url(get_template_directory_uri() . '/assets/images/movie-collection.jpg'); ?>);">
    <div class="container">
        <div class="inner">
            <h2 class="title">Martin Movies</h2>
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li>Martin Movies</li>
            </ol>
        </div>
    </div>
</section>
<main class="bg-light ptb100">
    <div class="container">
        <div class="row mb50">
            <div class="col-md-6">
                <div class="layout-switcher">
                    <a href="index.html" class="list"><i class="fa fa-align-justify"></i></a>
                    <a href="index.html" class="grid active"><i class="fa fa-th"></i></a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="sort-by">
                    <div class="sort-by-select">
                        <select class="chosen-select-no-single">
                            <option>Default Order</option>
                            <option>Featured</option>
                            <option>Top Viewed</option>
                            <option>Top Rated</option>
                            <option>Newest</option>
                            <option>Oldest</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row movie-listing">
            <?php
            $page = get_query_var('paged') ? get_query_var('paged') : 1;
            $keyword = get_query_var('keyword') ? get_query_var('keyword') : '';
            $api_response = fetch_movies_from_api($page, $keyword);
            $movies = isset($api_response['results']) ? $api_response['results'] : [];
            $total_pages = isset($api_response['total_pages']) ? min($api_response['total_pages'], 499) : 0; // Limit to 500 pages

            $movies_per_page = 6;
            $movies = array_slice($movies, 0, $movies_per_page);

            if (!empty($movies) && is_array($movies)) {            
            if (!empty($movies)) :
                foreach ($movies as $movie) :
                    $imdb_id = get_movie_imdb_id($movie['id']);
                    $poster_path = !empty($movie['poster_path']) ? 'https://image.tmdb.org/t/p/w500' . $movie['poster_path'] : 'assets/images/posters/poster-1.jpg';
                    $title = esc_html($movie['title']);
                    $overview = esc_html($movie['overview']);
                    $rating = !empty($movie['vote_average']) ? esc_html($movie['vote_average']) . '/10' : 'N/A';
                    $imdb_link = '#';
                    ?>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="movie-box-3 mb30">
                            <div class="listing-container">
                                <div class="listing-image">
                                    <img src="<?php echo esc_url($poster_path); ?>" alt="<?php echo $title; ?>">
                                </div>
                                <div class="listing-content">
                                    <div class="inner">
                                        <div class="play-btn">
                                            <a href="<?php echo $imdb_link; ?>" class="play-video">
                                                <i class="fa fa-play"></i>
                                            </a>
                                        </div>
                                        <h2 class="title"><?php echo $title; ?></h2>
                                        <div class="stars">
                                            <div class="rating">
                                                <i class="fa fa-star"></i>
                                                <span><?php echo $rating; ?></span>
                                                <span class="category">Action, Fantasy</span> 
                                            </div>
                                        </div>
                                        <p><?php echo $overview; ?></p>
                                        <a href="<?php echo $imdb_link; ?>" class="btn btn-main btn-effect">details</a>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                endforeach;

            else :
                echo '<p>No movies found.</p>';
            endif;
            } else {
            echo '<p>No movies found.</p>';
            }
            ?>
        </div>
        <div class="row">
                <?php 
                $pagination_links = paginate_links(array(
                        'total' => $total_pages,
                        'current' => $page,
                        'format' => '?paged=%#%',
                        'prev_text' => '<',
                        'next_text' => '>',
                        'type' => 'array',
                    ));

                    if (is_array($pagination_links)) :
                        ?>
                            <div class="col-md-12 col-sm-12">
                                <nav class="pagination">
                                    <?php foreach ($pagination_links as $link) : ?>
                                        <ul>
                                            <li <?php if (strpos($link, 'current') !== false) echo 'class="active"'; ?>>
                                                <?php echo $link; ?>
                                            </li>
                                        </ul>
                                    <?php endforeach; ?>
                                </nav>
                            </div>
                    <?php
                    endif;
                ?>
        </div>
    </div>
</main>



<?php get_footer(); ?>