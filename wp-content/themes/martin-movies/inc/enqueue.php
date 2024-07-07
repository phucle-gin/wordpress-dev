<?php

function enqueue_frontend() {
    // Dequeue existing scripts
    wp_dequeue_script('jquery');
    wp_deregister_script('jquery');
    

    // Third-party styles
    wp_enqueue_style('bootstrap', '//cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css');
    wp_enqueue_style('fontawesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    // My styles
	wp_enqueue_style('main-style', get_template_directory_uri().'/dist/css/main.min.css', array(), '1.1');
    // wp_enqueue_style('custom', get_template_directory_uri().'/dist/css/custom.css', array(), '1.1');
    

    // Third-party scripts
    wp_enqueue_script('jquery', '//code.jquery.com/jquery-3.7.1.min.js');
    wp_enqueue_script('popper-js', '//cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js', array('jquery'), '', true);
    wp_enqueue_script('bootstrap', '//cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js', array('jquery', 'popper-js'), '', true);


    // My scripts
    wp_enqueue_script('main-script', get_template_directory_uri().'/dist/js/main.min.js', '', '1.1', true);
    // Register custom variables for the AJAX script.

    wp_localize_script('main-script', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('ajax_nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_frontend');
?>