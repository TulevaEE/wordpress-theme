<?php
/**
 * Who may change a fund page's documents, and a record of every change.
 *
 * agent-gateway's fund_pages tool checks, approves (two people) and audits the writes it
 * makes over REST. This file covers the other routes: each write to a disclosure field
 * through ACF (wp-admin, REST, WP-Cron) and each revision restore is appended to a log on
 * the page once it is stored, and in wp-admin the fields are hidden from everyone but
 * Administrators, so a change goes through the gateway. A direct post-meta write (WP-CLI,
 * WPML's copy to a translation) is not logged; nothing reads a translation's copy.
 *
 * Who may write is decided by the writer, whatever the route: an Administrator, the
 * upcoming-document switch's WP-Cron job, or over REST an account with the Fund document
 * writer role that signs with an application password. Those accounts are agent-gateway's
 * and the ones the reports' Apps Script and onboarding-service publish the investment report
 * as. Anyone else is refused on every route; their REST request is answered 403 before
 * anything in it is saved.
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

/**
 * The capability that lets an account other than an Administrator write a disclosure field,
 * over REST and with an application password. The Fund document writer role carries it.
 */
const TULEVA_DISCLOSURE_WRITER = 'tuleva_write_fund_documents';

/**
 * The hook WP-Cron runs the upcoming-document switch on, hourly; it writes the fields while
 * this hook runs.
 */
const TULEVA_DISCLOSURE_SWITCH_HOOK = 'tuleva_promote_due_documents';

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
 * Whether the writer may change a disclosure field. Decided by who is writing, not by the
 * route, since an editor's logged-in session reaches REST with the wp-admin nonce and any
 * user can add an application password to their own profile. So: an Administrator; the
 * upcoming-document switch, while its hook runs, whoever's request WP-Cron runs it in
 * (ALTERNATE_WP_CRON runs it inside a visitor's or an editor's); or an account holding
 * TULEVA_DISCLOSURE_WRITER that signed this REST request with one of its application
 * passwords, since a browser logged in as that account is not the service that writes as it.
 * Anyone else is refused, any other cron run included.
 */
function tuleva_disclosure_may_write(string $route): bool
{
    if (current_user_can('manage_options') || doing_action(TULEVA_DISCLOSURE_SWITCH_HOOK)) {
        return true;
    }

    return $route === 'rest'
        && current_user_can(TULEVA_DISCLOSURE_WRITER)
        && function_exists('rest_get_authenticated_app_password')
        && rest_get_authenticated_app_password() !== null;
}

/**
 * The post an ACF object id names, or null for any other object (a term, user, comment,
 * option or block). ACF hands its filters the id as given, 17533, '17533' or 'post_17533',
 * and decodes it only when it writes, so the lock and the log decode it the same way: with
 * acf_decode_post_id() when ACF is loaded, otherwise as it reads its post forms.
 */
function tuleva_disclosure_post_id($post_id): ?int
{
    if (function_exists('acf_decode_post_id')) {
        $decoded = acf_decode_post_id($post_id);
        $is_post = ($decoded['type'] ?? '') === 'post' && is_numeric($decoded['id'] ?? null);
        $id = $is_post ? abs((int) $decoded['id']) : 0;
    } elseif (is_numeric($post_id)) {
        $id = abs((int) $post_id);
    } else {
        $is_post = is_string($post_id) && preg_match('/^(?:post|attachment|menu_item)_(\d+)$/', $post_id, $form);
        $id = $is_post ? (int) $form[1] : 0;
    }

    return $id > 0 ? $id : null;
}

/**
 * The meta key an ACF field's value is stored under. A sub-field's `_name` is only its short
 * name.
 */
function tuleva_disclosure_meta_key($field): string
{
    return is_array($field) ? (string) ($field['name'] ?? '') : '';
}

/**
 * The disclosure a REST `acf` key names, by the field's name or by its ACF key, which ACF
 * takes as well; '' for any other field. The keys are worked out once a request.
 */
function tuleva_disclosure_named(string $selector): string
{
    static $by_key = null;

    if ($by_key === null) {
        $by_key = [];
        foreach (tuleva_fund_disclosure_scope() as $template => $names) {
            foreach (array_keys($names) as $name) {
                $by_key[tuleva_fund_disclosure_field($name, $template)['key']] = $name;
            }
        }
    }

    return tuleva_is_disclosure($selector) ? $selector : ($by_key[$selector] ?? '');
}

/**
 * Answers a REST write that sets a disclosure field its writer may not set with 403, before
 * anything in it is saved. Left to ACF, the old value would be kept and the request answered
 * 200, and an unattended writer would record a success. This runs before the route's own
 * permission check, and once for each request of a batch, so a request without a user is
 * left to that check: a missing or refused credential is answered as WordPress answers it,
 * not as a missing role.
 */
function tuleva_disclosure_rest_guard($response, $handler, $request)
{
    if (
        is_wp_error($response)
        || !in_array($request->get_method(), ['POST', 'PUT', 'PATCH'], true)
        || !wp_get_current_user()->exists()
    ) {
        return $response;
    }

    $fields = $request->get_param('acf');
    $named = is_array($fields) ? array_filter(array_map('tuleva_disclosure_named', array_map('strval', array_keys($fields)))) : [];

    if ($named === [] || tuleva_disclosure_may_write('rest')) {
        return $response;
    }

    return new WP_Error(
        'tuleva_fund_document_locked',
        'Only an Administrator, or an account with the Fund document writer role signing with an'
            . ' application password, may set these fund document fields: '
            . implode(', ', array_unique($named)),
        ['status' => 403]
    );
}

add_filter('rest_request_before_callbacks', 'tuleva_disclosure_rest_guard', 10, 3);

/**
 * Stops ACF before it does anything with a disclosure value from a writer who may not set it
 * (tuleva_disclosure_may_write): returning anything but null here skips the field type's own
 * update_value, which would attach a posted document to the page, and the meta write, which
 * would leave a row behind. A REST request has been answered 403 by then; this covers
 * wp-admin, WP-Cron and anything else that writes through ACF.
 */
function tuleva_disclosure_pre_update($check, $value, $post_id, $field)
{
    if ($check !== null || tuleva_disclosure_post_id($post_id) === null || !tuleva_is_disclosure(tuleva_disclosure_meta_key($field))) {
        return $check;
    }

    return tuleva_disclosure_may_write(tuleva_disclosure_write_route()) ? null : false;
}

add_filter('acf/pre_update_value', 'tuleva_disclosure_pre_update', 10, 4);

/**
 * The same lock for ACF's deletions. delete_field(), and acf_delete_value() wherever it is
 * called from, do not pass acf/pre_update_value; ACF asks this filter before it deletes the
 * value and again before it deletes the field's reference row, and an answer other than null
 * stops each. So a writer who may not change the field leaves both rows as they are.
 */
function tuleva_disclosure_pre_delete($pre, $post_id, $name, $hidden = false)
{
    if ($pre !== null || tuleva_disclosure_post_id($post_id) === null || !tuleva_is_disclosure((string) $name)) {
        return $pre;
    }

    return tuleva_disclosure_may_write(tuleva_disclosure_write_route()) ? null : false;
}

add_filter('acf/pre_delete_metadata', 'tuleva_disclosure_pre_delete', 10, 4);

/**
 * A refused write stops at tuleva_disclosure_pre_update; should one get here anyway, the
 * stored value is kept, and null has ACF delete rather than write where nothing was stored,
 * so no empty row is left.
 */
function tuleva_disclosure_on_update($value, $post_id, $field)
{
    $name = tuleva_disclosure_meta_key($field);
    $page = tuleva_disclosure_post_id($post_id);

    if ($page === null || !tuleva_is_disclosure($name)) {
        return $value;
    }

    $before = get_post_meta($page, $name, true);

    if (!tuleva_disclosure_may_write(tuleva_disclosure_write_route())) {
        return $before === '' ? null : $before;
    }

    return $value;
}

add_filter('acf/update_value', 'tuleva_disclosure_on_update', 99, 3);

/**
 * Notes the change ACF is about to store in a disclosure field, as it stores it: the value
 * before, the value it writes (a deletion writes ''), and the route, to be logged once
 * WordPress has stored it (tuleva_disclosure_on_meta_write). ACF writes a field's value and
 * then the field's reference row, through these same filters, so the reference write ends
 * the note: a note applies to this one write, stored or not, and never to a later one.
 * Last, so a filter that short-circuits the write is seen. These filters get the id as given,
 * before ACF decodes it; the note is kept under the decoded one, which the meta hooks get. A
 * revision or an autosave (a preview) is not noted, since its log entry would land on the
 * page; the lock still applies to it.
 */
function tuleva_disclosure_note_write($pre, $post_id, $name, $value = '', $hidden = false)
{
    $post_id = tuleva_disclosure_post_id($post_id);

    if ($post_id === null || !tuleva_is_disclosure((string) $name)) {
        return $pre;
    }

    $pending = &tuleva_disclosure_writes_pending();
    unset($pending[$post_id][$name]);

    if ($pre !== null || $hidden || wp_is_post_revision($post_id)) {
        return $pre;
    }

    $before = get_post_meta($post_id, $name, true);

    if (tuleva_disclosure_scalar($before) !== tuleva_disclosure_scalar($value)) {
        $pending[$post_id][$name] = [
            'before' => $before,
            'after' => tuleva_disclosure_scalar($value),
            'route' => tuleva_disclosure_write_route(),
        ];
    }

    return $pre;
}

function tuleva_disclosure_note_delete($pre, $post_id, $name, $hidden = false)
{
    return tuleva_disclosure_note_write($pre, $post_id, $name, '', $hidden);
}

add_filter('acf/pre_update_metadata', 'tuleva_disclosure_note_write', PHP_INT_MAX, 5);
add_filter('acf/pre_delete_metadata', 'tuleva_disclosure_note_delete', PHP_INT_MAX, 4);

/**
 * @return array<int, array<string, array{before: mixed, after: string, route: string}>> the
 *     write ACF is making, by page and field, for this request
 */
function &tuleva_disclosure_writes_pending(): array
{
    static $pending = [];

    return $pending;
}

/**
 * Logs a change ACF noted, once WordPress has stored it: these hooks fire only after the row
 * is added, updated or deleted. Any other write to the key (WP-CLI, WPML's copy to a
 * translation, a revision's copy) has nothing noted, or another value, and is passed over.
 */
function tuleva_disclosure_on_meta_write($meta_id, $object_id, $meta_key, $meta_value): void
{
    $pending = &tuleva_disclosure_writes_pending();
    $write = $pending[(int) $object_id][(string) $meta_key] ?? null;

    if ($write === null || tuleva_disclosure_scalar($meta_value) !== $write['after']) {
        return;
    }

    unset($pending[(int) $object_id][(string) $meta_key]);
    tuleva_disclosure_record((int) $object_id, (string) $meta_key, $write['before'], $meta_value, $write['route']);
}

add_action('added_post_meta', 'tuleva_disclosure_on_meta_write', 10, 4);
add_action('updated_post_meta', 'tuleva_disclosure_on_meta_write', 10, 4);
add_action('deleted_post_meta', 'tuleva_disclosure_on_meta_write', 10, 4);

/**
 * Logs a stored change. This runs inside WordPress's and ACF's own writes, with the value
 * already stored, so a change the log cannot hold is left as it is and reported in the PHP
 * error log, one line: page, field, the values before and after, and the route.
 */
function tuleva_disclosure_record(int $post_id, string $name, $before, $after, string $route): void
{
    if (tuleva_disclosure_log($post_id, $name, $before, $after, $route)) {
        return;
    }

    error_log(sprintf(
        'Fund document log: could not record a change on page %d: %s %s -> %s (%s)',
        $post_id,
        $name,
        tuleva_disclosure_scalar($before) === '' ? 'empty' : tuleva_disclosure_scalar($before),
        tuleva_disclosure_scalar($after) === '' ? 'empty' : tuleva_disclosure_scalar($after),
        $route
    ));
}

/**
 * Appends one entry, and answers whether the log now holds it (or there was nothing to
 * hold). A write made while the upcoming-document switch runs is WP-Cron's, whoever's
 * request it runs in; every other write tuleva_disclosure_may_write lets through has a user.
 */
function tuleva_disclosure_log(int $post_id, string $name, $before, $after, string $route): bool
{
    $entry = tuleva_disclosure_log_entry(
        $name,
        $before,
        $after,
        doing_action(TULEVA_DISCLOSURE_SWITCH_HOOK) ? 'WP-Cron' : wp_get_current_user()->user_login,
        $route,
        gmdate('Y-m-d\TH:i:s\Z'),
        'wp_get_attachment_url'
    );

    return $entry === null || add_post_meta($post_id, TULEVA_DISCLOSURE_LOG, $entry) !== false;
}

/**
 * What the REST writers' calls need: read pages and media, upload a PDF (onboarding-service,
 * the reports' Apps Script), update a published page someone else wrote (all of them), read
 * and update the private shared documents page (agent-gateway), and TULEVA_DISCLOSURE_WRITER.
 * And unfiltered_html, as every Editor has: without it WordPress runs the stored page body and
 * title through kses on each update, even one that sets only fields, and strips any iframe,
 * script, form or SVG in them.
 */
function tuleva_disclosure_writer_capabilities(): array
{
    return [
        'read' => true,
        'upload_files' => true,
        'edit_pages' => true,
        'edit_others_pages' => true,
        'edit_published_pages' => true,
        'read_private_pages' => true,
        'edit_private_pages' => true,
        'unfiltered_html' => true,
        TULEVA_DISCLOSURE_WRITER => true,
    ];
}

/**
 * The role for the accounts that write the fields over REST. An Administrator gives it to an
 * account under Users in wp-admin. add_role stores a role in the options table, so it is
 * compared on every request and written only when it is missing or holds other capabilities
 * than these: one added or taken away elsewhere is put back, and changing what the role may
 * do is a change here. It is put back in one write of the roles option; WP_Role::add_cap and
 * remove_cap would each rewrite it.
 */
function tuleva_register_disclosure_writer_role(): void
{
    $capabilities = tuleva_disclosure_writer_capabilities();
    $role = get_role('fund_document_writer');

    if ($role === null) {
        add_role('fund_document_writer', 'Fund document writer', $capabilities);

        return;
    }

    if ($role->capabilities == $capabilities) {
        return;
    }

    $roles = wp_roles();
    $roles->roles['fund_document_writer']['capabilities'] = $capabilities;
    $role->capabilities = $capabilities;

    if ($roles->use_db) {
        update_option($roles->role_key, $roles->roles);
    }
}

add_action('init', 'tuleva_register_disclosure_writer_role');

/**
 * Hides a disclosure field from a wp-admin user who may not change it there
 * (tuleva_disclosure_may_write). Its value is in the Documents box under the fields.
 */
function tuleva_disclosure_prepare_field($field)
{
    $name = tuleva_disclosure_field_name($field);

    if (!tuleva_is_disclosure($name) || !is_admin()) {
        return $field;
    }

    return tuleva_disclosure_may_write(tuleva_disclosure_write_route()) ? $field : false;
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
 * Restoring a revision has ACF copy its fields straight into post meta, past
 * acf/update_value, and a revision holds the values of the last wp-admin save, not those
 * written since over REST or by WP-Cron. For a writer who may not change the disclosures
 * (tuleva_disclosure_may_write), ACF's copy leaves them and their references out: it reads
 * the revision through acf_get_meta(), whose result this filters. So nothing changes, and
 * nothing has to be put back afterwards over a value another request wrote meanwhile.
 * What the restore does write is logged from those writes (tuleva_disclosure_note_restore).
 */
function tuleva_disclosures_out_of_restore($meta, $post_id)
{
    if (
        !is_array($meta)
        || !doing_action('wp_restore_post_revision')
        || tuleva_disclosure_may_write(tuleva_disclosure_write_route())
    ) {
        return $meta;
    }

    foreach (array_keys(tuleva_fund_disclosure_catalogue()) as $name) {
        unset($meta[$name], $meta['_' . $name]);
    }

    return $meta;
}

add_filter('acf/load_meta', 'tuleva_disclosures_out_of_restore', 10, 2);

/**
 * Notes a write a revision restore makes to a disclosure field, as WordPress makes it, to be
 * logged once stored (tuleva_disclosure_on_meta_write) with the route 'revision'. ACF's
 * restore copies the revision's meta with update_metadata, past its own filters, so the note
 * is taken from WordPress's action before each write: update_post_meta for a row that
 * exists, add_post_meta for a new one, neither for a value that is unchanged. A field the
 * restore does not write gets no entry, whatever another request writes to it meanwhile.
 * ACF's copy to the latest revision is not the page's and is not noted.
 */
function tuleva_disclosure_note_restore($object_id, $meta_key, $meta_value): void
{
    $post_id = (int) $object_id;

    if (
        !doing_action('wp_restore_post_revision')
        || !tuleva_is_disclosure((string) $meta_key)
        || wp_is_post_revision($post_id)
    ) {
        return;
    }

    $pending = &tuleva_disclosure_writes_pending();
    $before = get_post_meta($post_id, $meta_key, true);
    unset($pending[$post_id][$meta_key]);

    if (tuleva_disclosure_scalar($before) !== tuleva_disclosure_scalar($meta_value)) {
        $pending[$post_id][$meta_key] = [
            'before' => $before,
            'after' => tuleva_disclosure_scalar($meta_value),
            'route' => 'revision',
        ];
    }
}

function tuleva_disclosure_note_restore_update($meta_id, $object_id, $meta_key, $meta_value): void
{
    tuleva_disclosure_note_restore($object_id, $meta_key, $meta_value);
}

function tuleva_disclosure_note_restore_add($object_id, $meta_key, $meta_value): void
{
    tuleva_disclosure_note_restore($object_id, $meta_key, $meta_value);
}

add_action('update_post_meta', 'tuleva_disclosure_note_restore_update', 10, 4);
add_action('add_post_meta', 'tuleva_disclosure_note_restore_add', 10, 3);

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

        if (!tuleva_disclosure_may_write(tuleva_disclosure_write_route())) {
            foreach (array_keys(tuleva_fund_disclosure_scope()[$template]) as $name) {
                $locked[$name] = tuleva_disclosure_stored_value($name, $post->ID);
            }
        }

        $source = tuleva_disclosure_source_post($post->ID);
        echo tuleva_disclosure_panel($locked, get_post_meta($source, TULEVA_DISCLOSURE_LOG, false) ?: []);
    }, 'page', 'normal');
});
