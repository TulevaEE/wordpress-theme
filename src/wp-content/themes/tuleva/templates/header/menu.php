<?php
if (has_nav_menu('primary_navigation')) {
    $args = [
        'theme_location'  => 'primary_navigation',
        'container'       => false,
        'menu_class'      => 'nav navbar-nav',
        'echo'            => true,
        'fallback_cb'     => 'wp_page_menu',
        'depth'           => 2,
    ];
    wp_nav_menu($args);
}
