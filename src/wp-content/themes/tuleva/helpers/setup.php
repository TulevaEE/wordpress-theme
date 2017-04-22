<?php

/**
 * Constants
 */
define('THEME_URL', get_template_directory_uri());
define('CAT', 'product_cat');
define('PRODUCTS_POST_TYPE', 'products');
define('TEXT_DOMAIN', 'ss');

/**
 * Theme setup
 */
function theme_setup() {
    // Make theme available for translation
    load_theme_textdomain(TEXT_DOMAIN, get_template_directory() . '/lang');

    // Enable plugins to manage the document title
    // http://codex.wordpress.org/Function_Reference/add_theme_support#Title_Tag
    add_theme_support('title-tag');

    // Register wp_nav_menu() menus
    // http://codex.wordpress.org/Function_Reference/register_nav_menus
    register_nav_menus([
        'primary_navigation' => __('Primary Navigation', TEXT_DOMAIN),
        'footer_nav_1' => __('Footer Navigation 1', TEXT_DOMAIN),
        'footer_nav_2' => __('Footer Navigation 2', TEXT_DOMAIN)
    ]);

    // Enable post thumbnails
    // http://codex.wordpress.org/Post_Thumbnails
    // http://codex.wordpress.org/Function_Reference/set_post_thumbnail_size
    // http://codex.wordpress.org/Function_Reference/add_image_size
    add_theme_support('post-thumbnails');

    // Enable post formats
    // http://codex.wordpress.org/Post_Formats
    add_theme_support('post-formats', ['aside', 'gallery', 'link', 'image', 'quote', 'video', 'audio']);

    // Enable HTML5 markup support
    // http://codex.wordpress.org/Function_Reference/add_theme_support#HTML5
    add_theme_support('html5', ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form']);

    // Use main stylesheet for visual editor
    // To add custom styles edit /assets/styles/layouts/_tinymce.scss
    add_editor_style(get_template_directory_uri() . 'css/main.css');

    // Image resize
    add_image_size('max-width-500', 500, 9999, false);
    add_image_size('max-width-1000', 1000, 9999, false);
    add_image_size('max-width-1920', 1920, 9999, false);
    add_image_size('max-height-width-100', 100, 100, false);
    add_image_size('max-height-400', 9999, 400, false);

    // Hides ACF menu item in admin
    // add_filter('acf/settings/show_admin', '__return_false');

    // Hides admin bar in front end
    add_filter('show_admin_bar', '__return_false');
}
add_action('after_setup_theme', 'theme_setup');
