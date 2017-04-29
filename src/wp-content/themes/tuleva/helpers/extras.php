<?php

/**
 * Returns post meta description value
 * @return string
 */
function get_meta_description() {
    $meta_desc = get_post_meta( get_queried_object_id(), '_tu_meta_desc', true );

    return $meta_desc;
}

/**
 * Prints post meta description tag
 * @return string
 */
function print_meta_description_tag() {
    $meta_desc = get_meta_description();

    if (!empty($meta_desc)) {
        echo '<meta name="description" content="' . $meta_desc . '">';
    }
}
add_action('wp_head', 'print_meta_description_tag');

/**
 * Adds custom logo to login page
 * @return void
 */
function custom_login_logo() { ?>
    <style type="text/css">
        #login h1 a,
        .login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/img/tuleva-logo.svg);
            background-size: 200px;
            height: 100px;
            width: 100%;
        }
    </style>
<?php }
add_action('login_enqueue_scripts', 'custom_login_logo');

/**
 * Adds WPML body class
 * @param  array $classes Default WPML body classes
 * @return array          Modified WPML body classes
 */
function wpml_body_class($classes) {
    if(defined('ICL_LANGUAGE_CODE')) {
        $classes[] = 'lang-' . ICL_LANGUAGE_CODE;
    }

    return $classes;
}
add_filter('body_class', 'wpml_body_class');

/**
 * Returns object or id which uses provided template
 * @param  string     $template Template file name
 * @param  boolean    $object   By default post id is returned. If set to true object is returned
 * @return int/object           Post ID or object is returned
 */
function get_template_post($template, $object = false) {
    $args = array(
                'post_type' => 'page',
                'meta_key' => '_wp_page_template',
                'meta_value' => $template,
                'meta_compare' => '=='
            );
    $template_query = null;
    $template_query = new WP_Query();
    $template_query->query( $args );
    $posts = $template_query->get_posts();
    $post = false;
    $return = false;

    if (!empty($posts)) {
        $post = $posts[0];
    }

    if (!empty($post) && $post->ID) {
        if ($object) {
            $return = $post;
        } else {
            $return = $post->ID;
        }
    }

    $template_query = null; wp_reset_postdata();

    return $return;
}

/**
 * Convert string to slug
 * @param  string $string Input: This is My Title
 * @return string         Output: this-is-my-title
 */
function to_slug($string){
    return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
}


/**
 * Make a URL relative
 */
function root_relative_url($input) {
    $url = parse_url($input);
    if (!isset($url['host']) || !isset($url['path'])) {
        return $input;
    }
    $site_url = parse_url(network_site_url());  // falls back to site_url

    if (!isset($url['scheme'])) {
        $url['scheme'] = $site_url['scheme'];
    }
    $hosts_match = $site_url['host'] === $url['host'];
    $schemes_match = $site_url['scheme'] === $url['scheme'];
    $ports_exist = isset($site_url['port']) && isset($url['port']);
    $ports_match = ($ports_exist) ? $site_url['port'] === $url['port'] : true;

    if ($hosts_match && $schemes_match && $ports_match) {
        return wp_make_link_relative($input);
    }
    return $input;
}

/**
 * Compare URL against relative URL
 */
function theme_url_compare($url, $rel) {
    $url = trailingslashit($url);
    $rel = trailingslashit($rel);

    return ((strcasecmp($url, $rel) === 0) || root_relative_url($url) == $rel);
}

/**
 * Hooks a single callback to multiple tags
 */
function add_filters($tags, $function, $priority = 10, $accepted_args = 1) {
    foreach ((array) $tags as $tag) {
        add_filter($tag, $function, $priority, $accepted_args);
    }
}

/**
 * Display error alerts in admin panel
 */
function alerts($errors, $capability = 'activate_plugins') {
    if (!did_action('init')) {
        return add_action('init', function () use ($errors, $capability) {
            alerts($errors, $capability);
        });
    }
    $alert = function ($message) {
        echo '<div class="error"><p>' . $message . '</p></div>';
    };
    if (call_user_func_array('current_user_can', (array) $capability)) {
        add_action('admin_notices', function () use ($alert, $errors) {
            array_map($alert, (array) $errors);
        });
    }
}

/**
 * Remove comments from admin
 */
function remove_unwanted_wp_menus() {
    remove_menu_page('edit-comments.php');
}
add_action( 'admin_menu', 'remove_unwanted_wp_menus' );

/**
 * Thumb upscale
 */
function thumbnail_upscale($default, $orig_w, $orig_h, $new_w, $new_h, $crop) {
    if ( !$crop ) return null;

    $aspect_ratio = $orig_w / $orig_h;
    $size_ratio = max($new_w / $orig_w, $new_h / $orig_h);

    $crop_w = round($new_w / $size_ratio);
    $crop_h = round($new_h / $size_ratio);

    $s_x = floor(($orig_w - $crop_w) / 2);
    $s_y = floor(($orig_h - $crop_h) / 2);

    return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );
}
add_filter( 'image_resize_dimensions', 'thumbnail_upscale', 10, 6 );

/**
 * List child pages
 */
function wpb_list_child_pages() {
    global $post;

    if ( is_page() && $post->post_parent ) {
        $childpages = wp_list_pages( 'sort_column=menu_order&title_li=&child_of=' . $post->post_parent . '&echo=0' );
    } else {
        $childpages = wp_list_pages( 'sort_column=menu_order&title_li=&child_of=' . $post->ID . '&echo=0' );
    }

    if ( $childpages ) {
        $string = '<ul>' . $childpages . '</ul>';
    }

    return $string;
}
add_shortcode('wpb_childpages', 'wpb_list_child_pages');
