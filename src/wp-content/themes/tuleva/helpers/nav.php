<?php

/**
 * Clean up wp_nav_menu_args
 *
 * Remove the container
 * Remove the id="" on nav menu items
 */
function theme_nav_menu_args($args = '') {
	$nav_menu_args = [];

	if (!$args['items_wrap']) {
		$nav_menu_args['items_wrap'] = '<ul class="%2$s">%3$s</ul>';
	}

	if (!$args['walker']) {
		/* Get file that contains NavWalker class */
		require_once (get_template_directory() . '/lib/class-wp-bootstrap-navwalker.php');
		$nav_menu_args['walker'] = new WP_Bootstrap_Navwalker();
	}

	return array_merge($args, $nav_menu_args);
}
add_filter('wp_nav_menu_args', 'theme_nav_menu_args');
add_filter('nav_menu_item_id', '__return_null');
