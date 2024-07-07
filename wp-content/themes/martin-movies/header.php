<!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <title><?php bloginfo('name'); ?> | <?php is_front_page() ? bloginfo('description') : wp_title(''); ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php bloginfo('description'); ?>">
    <?php wp_head(); ?>
  </head>
  <body <?php body_class(); ?>>
        <?php
            $movies_page_url = get_permalink(25);
        ?>
    <div class="loading" style="display: none;">
        <div class="loading-inner">
            <div class="loading-effect">
                <div class="object"></div>
            </div>
        </div>
    </div>
    <div class="wrapper">
    <header class="header">
            <div class="container-fluid">
                <nav class="navbar navbar-expand-lg">
                    <a class="navbar-brand" href="/">
                 
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.svg" alt="Martin Movies logo" width="150" class="logo-white">
                    </a>
                    <div class="navbar-collapse" id="main-nav">
                        <ul class="navbar-nav mx-auto" id="main-menu">
                            <li class="nav-item dropdown">
                                <a class="nav-link" href="/" aria-haspopup="true" aria-expanded="false">Home</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo esc_url($movies_page_url); ?>">Movies</a>
                            </li>
                        </ul>
                        <ul class="navbar-nav extra-nav">

                            <!-- Menu Item -->
                            <li class="nav-item">
                                <a class="nav-link toggle-search" href="#">
                                    <i class="fa fa-search"></i>
                                </a>
                            </li>

                            <!-- Menu Item -->
                            <li class="nav-item notification-wrapper">
                                <a class="nav-link notification" href="#">
                                    <i class="fa fa-globe"></i>
                                    <span class="notification-count">2</span>
                                </a>
                            </li>

                            <!-- Menu Item -->
                            <li class="nav-item m-auto">
                                <a href="#login-register-popup" class="btn btn-main btn-effect login-btn popup-with-zoom-anim">
                                    <i class="icon-user"></i>login
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </header>
      