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

/*
 * Get Tuleva member count
 */
stream_context_set_default(
    array(
        'http' => array(
            'method' => 'HEAD',
            'header' => array(
                "Authorization: Bearer b4adb192-29a8-4861-a697-c704947d0023"
            )
        )
    )
);
$headers = get_headers('https://onboarding-service.tuleva.ee/v1/members', 1);
$memberCount = $headers['X-Total-Count'];
