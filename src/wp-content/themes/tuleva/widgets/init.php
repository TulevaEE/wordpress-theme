<?php

function tu_register_widgets() {
    $widget_includes = [
        'widgets/subscribe-widget.php',
        'widgets/text-rows-widget.php',
        'widgets/cta-primary-widget.php',
        'widgets/cta-secondary-widget.php',
        'widgets/cta-button-widget.php',
    ];

    foreach ($widget_includes as $file) {
        if (!$filepath = locate_template($file)) {
            trigger_error(sprintf(__('Error locating %s for inclusion', 'theme'), $file), E_USER_ERROR);
        }

        require_once $filepath;
    }

    register_widget( 'Subscribe_Widget' );
    register_widget( 'Text_Rows_Widget' );
    register_widget( 'CTA_Primary_Widget' );
    register_widget( 'CTA_Secondary_Widget' );
    register_widget( 'CTA_Button_Widget' );


}
add_action( 'widgets_init', 'tu_register_widgets' );

/**
 * Register our sidebars and widgetized areas.
 *
 */
function tu_widgets_init() {

    register_sidebar( [
        'name'          => 'Footer widget area',
        'id'            => 'footer_widget_area',
        'before_widget' => '<div class="col-md footer__column">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4>',
        'after_title'   => '</h4>',
    ] );

    register_sidebar( [
        'name'          => 'Footer bottom widget area',
        'id'            => 'footer_bottom_widget_area',
        'before_widget' => '<div class="col-md-12 footer__text">',
        'after_widget'  => '</div>',
    ] );

    register_sidebar( [
        'name'          => 'Blog sidebar widget area',
        'id'            => 'blog_sidebar_widget_area',
        'before_widget' => '<div class="widget">',
        'after_widget'  => '</div>',
    ] );

    register_sidebar( [
        'name'          => 'Landing page widget area',
        'id'            => 'landing_page_widget_area',
        'before_widget' => '<div class="text-center row-spacing-half">',
        'after_widget'  => '</div>',
    ] );

}
add_action( 'widgets_init', 'tu_widgets_init' );
