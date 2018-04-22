<?php

$acf_includes = [
    'helpers/acf/page.php',
    'helpers/acf/fund.php', // Fund page fields
    'helpers/acf/front.php', // Front page fields
    'helpers/acf/blog.php', // Blog page fields
    'helpers/acf/contact.php', // Contact page fields
    'helpers/acf/options.php', // Options page
    'helpers/acf/membership.php', // Membership page
    'helpers/acf/fund-other.php' // Other fund pages
];

foreach ($acf_includes as $file) {
    if (!$filepath = locate_template($file)) {
        trigger_error(sprintf(__('Error locating %s for inclusion', 'theme'), $file), E_USER_ERROR);
    }

    require_once $filepath;
}
unset($file, $filepath);

/**
* Remove ACF menu item from the admin menu
*/
// add_action( 'admin_menu', 'acf_remove_menu_page', 15 );
// function acf_remove_menu_page() {
//     remove_menu_page( 'edit.php?post_type=acf' );
// }
