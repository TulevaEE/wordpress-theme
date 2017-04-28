<?php
/**
 * Theme includes
 *
 * The $theme_includes array determines the code library included in the theme.
 * Add or remove files to the array as needed.
 *
 * Please note that missing files will produce a fatal error.
 *
 */
$theme_includes = [
    'helpers/extras.php', // Extra functions for theme
    'helpers/cleanup.php', // Cleans wordpress output
    'helpers/enqueue.php', // Enqueue scripts & styles
    'helpers/nav.php', // Nav menu customizations
    'helpers/acf/init.php', // Init Advanced Custom Fields
    'helpers/post-types/init.php', // Init custom post types
    'widgets/init.php', // Init widgets
    'helpers/editor.php', // Editor customizations
    'helpers/setup.php', // Enqueue scripts & styles
];

foreach ($theme_includes as $file) {
    if (!$filepath = locate_template($file)) {
        trigger_error(sprintf(__('Error locating %s for inclusion', 'theme'), $file), E_USER_ERROR);
    }

    require_once $filepath;
}
unset($file, $filepath);
