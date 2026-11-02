<?php
/**
 * Who may change a fund page's documents in wp-admin, and a record of every change.
 *
 * agent-gateway's fund_pages tool checks, approves (two people) and audits the writes it
 * makes over REST. This file covers the other routes: each write to a disclosure field
 * through ACF (wp-admin, REST, WP-Cron) and each revision restore is appended to a log on
 * the page, and in wp-admin the fields are hidden from everyone but Administrators, so a
 * change goes through the gateway. A direct post-meta write (WP-CLI, WPML's copy to a
 * translation) is not logged; nothing reads a translation's copy.
 *
 * REST writes stay open to any user who may edit pages: the reports' Apps Script and
 * onboarding-service publish the investment report that way.
 *
 * See "TODO — Fund page document publishing", step 11, in the tuleva repo.
 */

/**
 * Post meta key of the log: one meta row per change, so appending never rewrites history.
 */
const TULEVA_DISCLOSURE_LOG = '_tuleva_disclosure_log';

/**
 * How many changes the edit screen lists, newest first.
 */
const TULEVA_DISCLOSURE_LOG_SHOWN = 50;

function tuleva_is_disclosure(string $name): bool
{
    return isset(tuleva_fund_disclosure_catalogue()[$name]);
}

/**
 * An ACF field's own name. When ACF renders a field it moves the name to `_name` and puts
 * the form input's name (`acf[field_…]`) in `name`.
 */
function tuleva_disclosure_field_name($field): string
{
    return is_array($field) ? (string) ($field['_name'] ?? $field['name'] ?? '') : '';
}

/**
 * A stored value as one string: an attachment id for a document, the text otherwise, ''
 * for nothing. ACF hands a file field over as an id, a numeric string or an attachment
 * array, and an empty one as null, false or '', depending on the route.
 */
function tuleva_disclosure_scalar($value): string
{
    if (is_array($value)) {
        $value = $value['ID'] ?? $value['id'] ?? '';
    }

    return $value === null || $value === false ? '' : (string) $value;
}

/**
 * The log entry for one write, or null when the value does not change. A document is
 * stored as an attachment id, so its URL is recorded beside it while there is one: the
 * attachment may be gone by the time anyone reads the log.
 *
 * @param callable(int): (string|false) $url_of
 */
function tuleva_disclosure_log_entry(
    string $name,
    $before,
    $after,
    string $who,
    string $route,
    string $at,
    callable $url_of
): ?array {
    $before = tuleva_disclosure_scalar($before);
    $after = tuleva_disclosure_scalar($after);

    if ($before === $after) {
        return null;
    }

    $entry = ['at' => $at, 'who' => $who, 'route' => $route, 'field' => $name, 'before' => $before, 'after' => $after];

    if ((tuleva_fund_disclosure_catalogue()[$name]['type'] ?? 'file') === 'file') {
        foreach (['before', 'after'] as $side) {
            $url = $entry[$side] === '' ? '' : (string) $url_of((int) $entry[$side]);
            if ($url !== '') {
                $entry[$side . '_url'] = $url;
            }
        }
    }

    return $entry;
}

/**
 * 'cron' for the upcoming-document switch, 'rest' for agent-gateway, the Apps Script and
 * onboarding-service, 'wp-admin' for the edit screen.
 */
function tuleva_disclosure_write_route(): string
{
    if (function_exists('wp_doing_cron') && wp_doing_cron()) {
        return 'cron';
    }

    if (defined('REST_REQUEST') && REST_REQUEST) {
        return 'rest';
    }

    return is_admin() ? 'wp-admin' : 'other';
}

/**
 * Logs a change to a disclosure field, and refuses one posted from wp-admin by a user who
 * may not make it there: the field is not on that user's screen, so such a value was
 * posted by hand.
 */
function tuleva_disclosure_on_update($value, $post_id, $field)
{
    // `name` is the meta key the value is stored under; a sub-field's `_name` is only its short name.
    $name = is_array($field) ? (string) ($field['name'] ?? '') : '';

    if (!is_numeric($post_id) || !tuleva_is_disclosure($name)) {
        return $value;
    }

    $before = get_post_meta((int) $post_id, $name, true);
    $route = tuleva_disclosure_write_route();

    if ($route === 'wp-admin' && !current_user_can('manage_options')) {
        return $before;
    }

    tuleva_disclosure_log((int) $post_id, $name, $before, $value, $route);

    return $value;
}

function tuleva_disclosure_log(int $post_id, string $name, $before, $after, string $route): void
{
    $user = wp_get_current_user();
    $entry = tuleva_disclosure_log_entry(
        $name,
        $before,
        $after,
        $user->exists() ? $user->user_login : ($route === 'cron' ? 'WP-Cron' : 'unknown'),
        $route,
        gmdate('Y-m-d\TH:i:s\Z'),
        'wp_get_attachment_url'
    );

    if ($entry !== null) {
        add_post_meta($post_id, TULEVA_DISCLOSURE_LOG, $entry);
    }
}

add_filter('acf/update_value', 'tuleva_disclosure_on_update', 99, 3);

/**
 * Hides a disclosure field from a wp-admin user who may not change it there. Its value is
 * in the Documents box under the fields.
 */
function tuleva_disclosure_prepare_field($field)
{
    $name = tuleva_disclosure_field_name($field);

    if (!tuleva_is_disclosure($name) || !is_admin()) {
        return $field;
    }

    return current_user_can('manage_options') ? $field : false;
}

add_filter('acf/prepare_field', 'tuleva_disclosure_prepare_field');

/**
 * Keeps the disclosures out of WordPress's own Custom Fields form, whose save handler writes post
 * meta directly, past acf/update_value: a value posted there would change a document with no lock
 * and no log. ACF and REST write through their own paths, which this does not touch.
 */
function tuleva_disclosure_is_protected_meta($protected, $meta_key, $meta_type)
{
    return $protected || ($meta_type === 'post' && tuleva_is_disclosure((string) $meta_key));
}

add_filter('is_protected_meta', 'tuleva_disclosure_is_protected_meta', 10, 3);

/**
 * Restoring a revision copies its fields straight into post meta, past acf/update_value,
 * and a revision holds the values of the last wp-admin save, not those written since over
 * REST or by WP-Cron. So the disclosures are read before ACF's restore (priority 10) and
 * compared after it: a user who may not change one in wp-admin gets it put back, anyone
 * else's change is logged.
 */
function tuleva_disclosures_before_restore($post_id): void
{
    $held = &tuleva_disclosures_held_over_restore();
    foreach (array_keys(tuleva_fund_disclosure_catalogue()) as $name) {
        $held[(int) $post_id][$name] = get_post_meta((int) $post_id, $name, true);
    }
}

function tuleva_disclosures_after_restore($post_id): void
{
    $post_id = (int) $post_id;
    $is_administrator = current_user_can('manage_options');
    $held = &tuleva_disclosures_held_over_restore();

    foreach ($held[$post_id] ?? [] as $name => $before) {
        $after = get_post_meta($post_id, $name, true);

        if (tuleva_disclosure_scalar($before) === tuleva_disclosure_scalar($after)) {
            continue;
        }

        if ($is_administrator) {
            tuleva_disclosure_log($post_id, $name, $before, $after, 'revision');
        } else {
            update_post_meta($post_id, $name, $before);
        }
    }

    unset($held[$post_id]);
}

/**
 * @return array<int, array<string, mixed>> the disclosures as they stood before a restore, by page
 */
function &tuleva_disclosures_held_over_restore(): array
{
    static $held = [];

    return $held;
}

add_action('wp_restore_post_revision', 'tuleva_disclosures_before_restore', 5);
add_action('wp_restore_post_revision', 'tuleva_disclosures_after_restore', 20);

/**
 * The Documents box: each read-only field's value, then the change log.
 *
 * @param array<string, string> $locked field name => what it holds (a URL or a figure)
 * @param list<array<string, string>> $log entries, oldest first, as stored
 */
function tuleva_disclosure_panel(array $locked, array $log): string
{
    $html = '';

    if ($locked !== []) {
        $html .= '<p>These are changed through agent-gateway (<code>fund_pages</code>), which asks a'
            . ' second person to approve in Slack, and the investment report by the monthly report job.</p>'
            . '<table class="widefat striped"><tbody>';
        foreach ($locked as $name => $value) {
            $html .= '<tr><th>' . esc_html($name) . '</th><td>' . tuleva_disclosure_shown($value) . '</td></tr>';
        }
        $html .= '</tbody></table>';
    }

    $html .= '<h4>Changes</h4>';

    if ($log === []) {
        return $html . '<p>None recorded.</p>';
    }

    $html .= '<table class="widefat striped"><thead><tr><th>When (UTC)</th><th>Who</th><th>How</th>'
        . '<th>Field</th><th>Before</th><th>After</th></tr></thead><tbody>';
    foreach (array_slice(array_reverse($log), 0, TULEVA_DISCLOSURE_LOG_SHOWN) as $entry) {
        $html .= '<tr><td>' . esc_html($entry['at'] ?? '') . '</td><td>' . esc_html($entry['who'] ?? '')
            . '</td><td>' . esc_html($entry['route'] ?? '') . '</td><td>' . esc_html($entry['field'] ?? '')
            . '</td><td>' . tuleva_disclosure_shown($entry['before_url'] ?? $entry['before'] ?? '')
            . '</td><td>' . tuleva_disclosure_shown($entry['after_url'] ?? $entry['after'] ?? '') . '</td></tr>';
    }

    return $html . '</tbody></table>';
}

function tuleva_disclosure_shown(string $value): string
{
    if ($value === '') {
        return '<em>empty</em>';
    }

    return str_starts_with($value, 'http')
        ? '<a href="' . esc_url($value) . '">' . esc_html(basename($value)) . '</a>'
        : esc_html($value);
}

add_action('add_meta_boxes_page', function ($post): void {
    $template = get_page_template_slug($post);

    if (!isset(tuleva_fund_disclosure_scope()[$template])) {
        return;
    }

    add_meta_box('tuleva-disclosures', 'Documents', function ($post) use ($template): void {
        $locked = [];

        if (!current_user_can('manage_options')) {
            foreach (array_keys(tuleva_fund_disclosure_scope()[$template]) as $name) {
                $locked[$name] = tuleva_disclosure_stored_value($name, $post->ID);
            }
        }

        $source = tuleva_disclosure_source_post($post->ID);
        echo tuleva_disclosure_panel($locked, get_post_meta($source, TULEVA_DISCLOSURE_LOG, false) ?: []);
    }, 'page', 'normal');
});
