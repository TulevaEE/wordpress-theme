<?php

/**
 * Returns post meta description value
 * @return string
 */
function get_meta_description()
{
    $meta_desc = get_post_meta(get_queried_object_id(), '_tu_meta_desc', true);

    return $meta_desc;
}

/**
 * Changes default excerpt more
 * @param  string $more Current "more" string
 * @return string       Returns modified "more" string
 */
function custom_excerpt_more($more)
{
    return '...';
}
add_filter('excerpt_more', 'custom_excerpt_more');

/**
 * Prints language links
 * @param  boolean $isMobile Is visible in mobile or desktop
 * @return void
 */
function language_picker($isMobile = false)
{
    $languages = icl_get_languages('skip_missing=0&orderby=code');

    if (!empty($languages)) { ?>
        <?php
        foreach ($languages as $l) {
            if ($l['code'] === 'ru') {
                continue;
            }

            // two language pickers: desktop and mobile have different styles
            $link = '<a href="' . $l['url'] . '" class="nav-langpicker btn btn-link fw-medium text-uppercase">' . icl_disp_language($l['code']) . '</a><a href="' . $l['url'] . '" class="nav-langpicker btn btn-outline-primary fw-medium text-uppercase">' . icl_disp_language($l['code']) . '</a>';

            if (!$l['active']) {
                echo $link;
            }
        }
        ?>
    <?php
    }

    unset($languages);
}

/**
 * Returns string of classes for component container
 * @param  array/string  $classes Array or string of classes to be joined
 * @return string                 String of classes joined together
 */
function get_component_classes($classes = [])
{
    if (!empty($classes) && is_string($classes)) {
        $classes = [$classes];
    }

    if (get_sub_field('has_background_color')) {
        $classes[] = 'bg-alt';
    }

    if (get_sub_field('spacing') === 'half') {
        $classes[] = 'section-spacing-half';
    } else {
        $classes[] = 'section-spacing';
    }

    return implode(' ', $classes);
}

function get_component_background_color_class() {
    $bg_class = strtolower(get_sub_field('background_color')) === 'blue' ? 'bg-blue-2' : '';

    return $bg_class;
}

function get_component_button_color_class() {
    $button_color = strtolower(get_sub_field('button_color'));
    $button_color_class = '';

    if ($button_color === 'blue') {
        $button_color_class = 'btn-primary';
    } else if ($button_color === 'outlined') {
        $button_color_class = 'btn-outline-primary';
    } else if ($button_color === 'orange') {
        $button_color_class = 'btn-orange';
    }

    return $button_color_class;
}

function get_qa_question_wrapper_classes($classes, $currentNr, $visibleQuestionsCount) {
    if ($currentNr > $visibleQuestionsCount) {
        $classes[] = 'qa__question-wrapper--collapsable';
        $classes[] = 'qa__question-wrapper--collapsed';
    }

    return implode(' ', $classes);
}

/**
 * Adds custom logo to login page
 * @return void
 */
function customize_login_page_logo()
{ ?>
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
add_action('login_enqueue_scripts', 'customize_login_page_logo');

/**
 * Adds WPML body class
 * @param  array $classes Default WPML body classes
 * @return array          Modified WPML body classes
 */
function wpml_body_class($classes)
{
    if (defined('ICL_LANGUAGE_CODE')) {
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
function get_template_post($template, $object = false)
{
    $args = [
        'post_type' => 'page',
        'meta_key' => '_wp_page_template',
        'meta_value' => $template,
        'meta_compare' => '=='
    ];
    $template_query = null;
    $template_query = new WP_Query();
    $template_query->query($args);
    $posts = $template_query->get_posts();
    $post = false;
    $return = null;

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

    $template_query = null;
    wp_reset_postdata();

    return $return;
}

/**
 * Convert string to slug
 * @param  string $string Input: This is My Title
 * @return string         Output: this-is-my-title
 */
function to_slug($string)
{
    return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
}


/**
 * Make a URL relative
 */
function root_relative_url($input)
{
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
function theme_url_compare($url, $rel)
{
    $url = trailingslashit($url);
    $rel = trailingslashit($rel);

    return ((strcasecmp($url, $rel) === 0) || root_relative_url($url) == $rel);
}

/**
 * Hooks a single callback to multiple tags
 */
function add_filters($tags, $function, $priority = 10, $accepted_args = 1)
{
    foreach ((array) $tags as $tag) {
        add_filter($tag, $function, $priority, $accepted_args);
    }
}

/**
 * Display error alerts in admin panel
 */
function alerts($errors, $capability = 'activate_plugins')
{
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
function remove_unwanted_wp_menus()
{
    remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'remove_unwanted_wp_menus');

/**
 * Thumb upscale
 */
function thumbnail_upscale($default, $orig_w, $orig_h, $new_w, $new_h, $crop)
{
    if (!$crop) return null;

    $aspect_ratio = $orig_w / $orig_h;
    $size_ratio = max($new_w / $orig_w, $new_h / $orig_h);

    $crop_w = round($new_w / $size_ratio);
    $crop_h = round($new_h / $size_ratio);

    $s_x = floor(($orig_w - $crop_w) / 2);
    $s_y = floor(($orig_h - $crop_h) / 2);

    return array(0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h);
}
add_filter('image_resize_dimensions', 'thumbnail_upscale', 10, 6);

/**
 * List child pages
 */
function wpb_list_child_pages()
{
    global $post;

    if (is_page() && $post->post_parent) {
        $childpages = wp_list_pages('sort_column=menu_order&title_li=&child_of=' . $post->post_parent . '&echo=0');
    } else {
        $childpages = wp_list_pages('sort_column=menu_order&title_li=&child_of=' . $post->ID . '&echo=0');
    }

    if ($childpages) {
        $string = '<ul>' . $childpages . '</ul>';
    }

    return $string;
}
add_shortcode('wpb_childpages', 'wpb_list_child_pages');

function get_app_url($path)
{
    return 'https://pension.tuleva.ee' . $path . '?language=' . ICL_LANGUAGE_CODE;
}

/*
 * Get Tuleva member count
 */
function get_member_count()
{
    stream_context_set_default(
        array(
            'http' => array(
                'method' => 'HEAD'
            )
        )
    );
    $headers = get_headers('https://onboarding-service.tuleva.ee/v1/members', 1);
    $memberCount = empty($headers['X-Total-Count']) ? 0 : $headers['X-Total-Count'];

    return $memberCount;
}

function print_funds_js()
{
    $context = stream_context_create(
        [
            'http' => [
                'method' => 'GET',
                'timeout' => 1,
            ]
        ]
    );
    $json = file_get_contents('https://onboarding-service.tuleva.ee/v1/funds', false, $context);
    $data = json_decode($json, true);

    if (empty($data)) {
        return;
    }

    $filtered = array_filter($data, function ($value, $key) {
        $isActive = $value['status'] === 'ACTIVE';
        $isSecondPillar = $value['pillar'] === 2;
        $isNotTulevaFund = $value['fundManager']['name'] !== 'Tuleva';

        return $isActive && $isSecondPillar && $isNotTulevaFund;
    }, ARRAY_FILTER_USE_BOTH);
    $funds = array_map(function ($value) {
        return [
            'name' => $value['name'],
            'fee' => $value['ongoingChargesFigure']
        ];
    }, $filtered);
    usort($funds, function ($a, $b) {
        return strcmp($a['name'], $b['name']);
    });

    echo '<script type="text/javascript">';
    echo 'var calculatorFunds = ' . json_encode($funds) . ';';
    echo '</script>';
}

function get_esg_document_url() {
    $esg_document_path = '/wp-content/uploads/2024/07/Principles-for-considering-sustainability-risks_30.06.2024.pdf';

    if (ICL_LANGUAGE_CODE == 'et') {
        $esg_document_path = '/wp-content/uploads/2024/06/Tuleva-jatkusuutlikkusriskidega-arvestamise-poliitika_30.06.2023.pdf';
    }

    return get_site_url() . $esg_document_path;
}

function get_esg_factors_document_url() {
    $esg_factors_document_path = '/wp-content/uploads/2024/08/Non-consideration-of-adverse-impacts-of-investment-decisions-on-sustainability-factors_30.06.2024.pdf';

    if (ICL_LANGUAGE_CODE == 'et') {
        $esg_factors_document_path = '/wp-content/uploads/2024/06/Investeerimisotsuste-poolt-kestlikkusteguritele-avaldatava-negatiivse-moju-mittearvestamine_30.06.2024.pdf';
    }

    return get_site_url() . $esg_factors_document_path;
}

function get_remuneration_document_url() {
    $renumeration_document_path = '/wp-content/uploads/2024/08/Remuneration-Policy-of-Tuleva-Fondid-AS.pdf';

    if (ICL_LANGUAGE_CODE == 'et') {
        $renumeration_document_path = '/wp-content/uploads/2024/07/Tuleva-Fondid-AS-tasustamise-pohimotted-.pdf';
    }

    return get_site_url() . $renumeration_document_path;

}

function hyphenate_string($string) {
    return strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $string));
}

function generate_report_link($url, $link_text = null) {
    preg_match('/\/(\d{4})\/(\d{2})\//', $url, $matches);
    $year = intval($matches[1]);
    $month = intval($matches[2]);

    $month--;
    if ($month === 0) {
        $month = 12;
        $year--;
    }

    $year_str = strval($year);
    $month_str = str_pad(strval($month), 2, '0', STR_PAD_LEFT);

    $default_text = sprintf('%s.%s', $month_str, $year_str);
    if ($link_text !== null) {
        $link_text = sprintf('%s (%s)', $link_text, $default_text);
    } else {
        $link_text = $default_text;
    }

    if (filter_var($url, FILTER_VALIDATE_URL)) {
        $path = parse_url($url, PHP_URL_PATH);
    } else {
        $path = $url;
    }

    $output = sprintf(
        '<a href="%s%s" target="_blank">%s</a>',
        get_site_url(),
        $path,
        $link_text
    );

    return $output;
}

function countdown_timer_function($atts) {
    $atts = shortcode_atts(array(
        'datetime' => 'now',
        'timezone' => get_option('timezone_string')
    ), $atts, 'countdown_timer');

    try {
        $userTimezone = new DateTimeZone($atts['timezone']);
        $now = new DateTime('now', $userTimezone);

        $target_datetime = new DateTime($atts['datetime'], $userTimezone);

        if ($now > $target_datetime) {
            $days_text = __('days', TEXT_DOMAIN);
            $days_remaining_html_output = "<span class=\"countdown-timer__item\"><span class=\"countdown-timer__days\">0</span> {$days_text}</span>";

            return "<span class='countdown-timer'>{$days_remaining_html_output}</span>";
        }

        $difference = $now->diff($target_datetime);
        $hours_until = (int)$difference->format('%a') * 24 + (int)$difference->format('%h');

        if ($hours_until > 48) {
            $days_count = $difference->format('%a');
            $days_text = __('days', TEXT_DOMAIN);
            $days_remaining_html_output = "<span class=\"countdown-timer__item\"><span class=\"countdown-timer__days\">{$days_count}</span> {$days_text}</span>";
            $html_output = "<span class='countdown-timer'>{$days_remaining_html_output}</span>";
        } else {
        $total_seconds = $difference->days * 24 * 60 * 60
            + $difference->h * 60 * 60
            + $difference->i * 60
            + $difference->s;

        $hours = str_pad(floor($total_seconds / 3600), 2, "0", STR_PAD_LEFT);
        $minutes = str_pad(floor(($total_seconds / 60) % 60), 2, "0", STR_PAD_LEFT);
        $seconds = str_pad($total_seconds % 60, 2, "0", STR_PAD_LEFT);

        $hours_html_output = "<span class=\"countdown-timer__item\"><span class=\"countdown-timer__hours\">{$hours}</span>h</span>";
        $minutes_html_output = "<span class=\"countdown-timer__item\"><span class=\"countdown-timer__minutes\">{$minutes}</span>m</span>";
        $seconds_html_output = "<span class=\"countdown-timer__item\"><span class=\"countdown-timer__seconds\">{$seconds}</span>s</span>";
        $time_remaining_html_output = "{$hours_html_output} {$minutes_html_output} {$seconds_html_output}";
        $html_output = "<span class=\"countdown-timer\" data-datetime=\"{$target_datetime->format(DateTime::ISO8601)}\">{$time_remaining_html_output}</span>";
        }
    } catch (Exception $error) {
        $error_message = $error->getMessage();
        $html_output = "<span class=\"countdown-timer d-none\">{$error_message}</span>";
    }

    return $html_output;
}

add_shortcode('countdown_timer', 'countdown_timer_function');

function payout_calculator_shortcode() {
    ob_start();
    get_template_part('templates/components/payout-calculator');
    return ob_get_clean();
}
add_shortcode('payout_calculator', 'payout_calculator_shortcode');

function third_pillar_calculator_shortcode() {
    ob_start();
    get_template_part('templates/components/third-pillar-calculator');
    return ob_get_clean();
}
add_shortcode('third_pillar_calculator', 'third_pillar_calculator_shortcode');

function term_link_domain_fix($term_link) {
    $site_url = get_site_url();
    $parsed_site_url = parse_url($site_url);
    $parsed_term_link = parse_url($term_link);
    $parsed_term_link['host'] = $parsed_site_url['host'];

    $fixed_url = $parsed_term_link['scheme'] . '://' . $parsed_term_link['host'] . $parsed_term_link['path'];

    if (isset($parsed_term_link['query'])) {
        $fixed_url .= '?' . $parsed_term_link['query'];
    }

    return $fixed_url;
}
add_filter('term_link', 'term_link_domain_fix', 20);

function force_valid_domain_redirect() {
    $valid_domain = parse_url(WP_SITEURL, PHP_URL_HOST);
    $current_host_without_port = explode(':', $_SERVER['HTTP_HOST'])[0];

    if ($current_host_without_port !== $valid_domain) {
        $protocol = is_ssl() ? 'https://' : 'http://';
        wp_redirect($protocol . $valid_domain . $_SERVER['REQUEST_URI'], 301);
        exit;
    }
}
//add_action('template_redirect', 'force_valid_domain_redirect');

function blog_pagination() {
    if( is_singular() )
        return;

    global $wp_query;

    /** Stop execution if there's only 1 page */
    if( $wp_query->max_num_pages <= 1 ) {
        return;
    }

    $paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
    $max   = intval( $wp_query->max_num_pages );

    /** Add current page to the array */
    if ( $paged >= 1 ) {
        $links[] = $paged;
    }

    /** Add the pages around the current page to the array */
    if ( $paged >= 3 ) {
        $links[] = $paged - 1;
        $links[] = $paged - 2;
    }

    if ( ( $paged + 2 ) <= $max ) {
        $links[] = $paged + 2;
        $links[] = $paged + 1;
    }

    echo '<nav class="pagination" aria-label="' . esc_attr__( 'Pagination', TEXT_DOMAIN ) . '"><ul>' . "\n";

    /** Previous Post Link */
    if ( get_previous_posts_link() ) {
        printf( '<li class="pagination__previous">%s</li>' . "\n", get_previous_posts_link(__('Newer', TEXT_DOMAIN)) );
    }

    /** Link to first page, plus ellipses if necessary */
    if ( ! in_array( 1, $links ) ) {
        $class = 1 == $paged ? ' class="active"' : '';

        printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );

        if ( ! in_array( 2, $links ) )
            echo '<li class="pagination__ellipsis">…</li>';
    }

    /** Link to current page, plus 2 pages in either direction if necessary */
    sort( $links );
    foreach ( (array) $links as $link ) {
        if ( $paged == $link ) {
            printf(
                '<li class="pagination__active"><a href="%s" aria-current="page">%s</a></li>' . "\n",
                esc_url( get_pagenum_link( $link ) ),
                $link
            );
        } else {
            printf(
                '<li><a href="%s">%s</a></li>' . "\n",
                esc_url( get_pagenum_link( $link ) ),
                $link
            );
        }
    }

    /** Link to last page, plus ellipses if necessary */
    if ( ! in_array( $max, $links ) ) {
        if ( ! in_array( $max - 1, $links ) ) {
            echo '<li class="pagination__ellipsis">…</li>' . "\n";
        }

        $class = $paged == $max ? ' class="active"' : '';
        printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
    }

    /** Next Post Link */
    if ( get_next_posts_link() ) {
        printf( '<li class="pagination__next">%s</li>' . "\n", get_next_posts_link(__('Older', TEXT_DOMAIN)) );
    }

    echo '</ul></nav>' . "\n";
}

function highlight_search_results($text){
     if(is_search() && get_search_query()){
          $keys = implode('|', explode(' ', get_search_query()));
            $text = preg_replace('/(' . $keys . ')/iu', '<strong class="text-highlight">$1</strong>', $text);
     }

     return $text;
}
add_filter('the_excerpt', 'highlight_search_results');
