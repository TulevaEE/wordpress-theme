<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\Attributes\Test;

/**
 * Stands in for the request a write to a disclosure field arrives in: who is logged in, what
 * they may do, which screen and hook, how a REST request was signed, and the page's post meta. A REST
 * request is the REST_REQUEST constant, which a test defines in a process of its own.
 */
final class FakeDisclosureWrite
{
    public static bool $in_admin = false;
    public static bool $in_cron = false;
    public static bool $administrator = false;
    public static bool $writer = false;
    public static ?string $application_password = null;
    public static string $login = '';
    public static ?string $doing_action = null;
    public static bool $log_fails = false;

    /** @var array<int, array<string, list<mixed>>> */
    public static array $meta = [];

    public static function reset(): void
    {
        self::$in_admin = false;
        self::$in_cron = false;
        self::$administrator = false;
        self::$writer = false;
        self::$application_password = null;
        self::$login = '';
        self::$doing_action = null;
        self::$log_fails = false;
        self::$meta = [];
    }

    /**
     * An account with the writer role, signing with one of its application passwords.
     */
    public static function writerSigning(string $login): void
    {
        self::$writer = true;
        self::$application_password = 'a-uuid';
        self::$login = $login;
    }
}

/**
 * Stands in for WP_REST_Request: the method and the parameters, JSON body included.
 */
final class FakeRestRequest
{
    public function __construct(private string $method, private array $params)
    {
    }

    public function get_method(): string
    {
        return $this->method;
    }

    public function get_param($key)
    {
        return $this->params[$key] ?? null;
    }
}

final class FakeDisclosureUser
{
    public function __construct(public string $user_login)
    {
    }

    public function exists(): bool
    {
        return $this->user_login !== '';
    }
}

/**
 * Guarded because FundDisclosuresTest and ReportLinkTest stand up some of the same ones
 * and PHPUnit runs every file in one process; the definitions match.
 */
if (!function_exists('add_filter')) {
    function add_filter($hook, $callback, $priority = 10, $args = 1)
    {
    }
}

if (!function_exists('add_action')) {
    function add_action($hook, $callback, $priority = 10, $args = 1)
    {
    }
}

if (!function_exists('esc_html')) {
    function esc_html($value)
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('is_admin')) {
    function is_admin()
    {
        return FakeDisclosureWrite::$in_admin;
    }
}

if (!function_exists('wp_doing_cron')) {
    function wp_doing_cron()
    {
        return FakeDisclosureWrite::$in_cron;
    }
}

if (!function_exists('doing_action')) {
    function doing_action($hook_name = null)
    {
        return $hook_name !== null && $hook_name === FakeDisclosureWrite::$doing_action;
    }
}

if (!function_exists('rest_get_authenticated_app_password')) {
    function rest_get_authenticated_app_password()
    {
        return FakeDisclosureWrite::$application_password;
    }
}

if (!function_exists('current_user_can')) {
    function current_user_can($capability, ...$args)
    {
        return match ($capability) {
            'manage_options' => FakeDisclosureWrite::$administrator,
            TULEVA_DISCLOSURE_WRITER => FakeDisclosureWrite::$writer,
            default => false,
        };
    }
}

if (!function_exists('wp_get_current_user')) {
    function wp_get_current_user()
    {
        return new FakeDisclosureUser(FakeDisclosureWrite::$login);
    }
}

if (!function_exists('get_post_meta')) {
    function get_post_meta($post_id, $key = '', $single = false)
    {
        $values = FakeDisclosureWrite::$meta[$post_id][$key] ?? [];

        return $single ? ($values[0] ?? '') : $values;
    }
}

/**
 * As WordPress's: given a previous value, only a row that still holds it is changed.
 */
if (!function_exists('update_post_meta')) {
    function update_post_meta($post_id, $meta_key, $meta_value, $prev_value = '')
    {
        $stored = FakeDisclosureWrite::$meta[$post_id][$meta_key][0] ?? '';
        if ($prev_value !== '' && (string) $stored !== (string) $prev_value) {
            return false;
        }

        FakeDisclosureWrite::$meta[$post_id][$meta_key] = [$meta_value];

        return true;
    }
}

if (!function_exists('delete_post_meta')) {
    function delete_post_meta($post_id, $meta_key, $meta_value = '')
    {
        $stored = FakeDisclosureWrite::$meta[$post_id][$meta_key][0] ?? null;
        if ($stored === null || ($meta_value !== '' && (string) $stored !== (string) $meta_value)) {
            return false;
        }

        unset(FakeDisclosureWrite::$meta[$post_id][$meta_key]);

        return true;
    }
}

if (!function_exists('add_post_meta')) {
    function add_post_meta($post_id, $meta_key, $meta_value, $unique = false)
    {
        if ($meta_key === TULEVA_DISCLOSURE_LOG && FakeDisclosureWrite::$log_fails) {
            return false;
        }

        FakeDisclosureWrite::$meta[$post_id][$meta_key][] = $meta_value;

        return true;
    }
}

require_once __DIR__ . '/../support/FakeEscaping.php';
require_once __DIR__ . '/../support/FakeAttachments.php';
require_once __DIR__ . '/../support/FakeRoles.php';
require_once __DIR__ . '/../support/FakeWordPress.php';
require_once __DIR__ . '/../support/FakeOptions.php';
require_once __DIR__ . '/../../helpers/acf/fund-disclosures.php';
require_once __DIR__ . '/../../helpers/acf/fund-disclosure-log.php';

final class FundDisclosureLogTest extends TestCase
{
    private const PAGE = 17533;

    protected function setUp(): void
    {
        FakeDisclosureWrite::reset();
        FakeAttachments::reset();
        FakeRoles::reset();
        FakeOptions::reset();
        $pending = &tuleva_disclosure_writes_pending();
        $pending = [];
        $held = &tuleva_disclosures_held_over_restore();
        $held = [];
    }

    #[Test]
    public function a_document_change_is_logged_with_both_urls(): void
    {
        $urls = [101 => 'https://tuleva.ee/a.pdf', 102 => 'https://tuleva.ee/b.pdf'];

        $entry = tuleva_disclosure_log_entry(
            'prospectus_file',
            '101',
            102,
            'agent-gateway',
            'rest',
            '2026-12-01T10:00:00Z',
            fn(int $id) => $urls[$id]
        );

        $this->assertSame([
            'at' => '2026-12-01T10:00:00Z',
            'who' => 'agent-gateway',
            'route' => 'rest',
            'field' => 'prospectus_file',
            'before' => '101',
            'after' => '102',
            'before_url' => 'https://tuleva.ee/a.pdf',
            'after_url' => 'https://tuleva.ee/b.pdf',
        ], $entry);
    }

    /**
     * ACF hands a value over in a different shape depending on the route, so the same
     * document or the same emptiness must not read as a change.
     */
    #[Test]
    public function a_value_that_only_changes_shape_is_not_logged(): void
    {
        $url_of = fn(int $id) => 'unused';

        $this->assertNull(tuleva_disclosure_log_entry('prospectus_file', '101', 101, 'x', 'rest', 'now', $url_of));
        $this->assertNull(tuleva_disclosure_log_entry('prospectus_file', ['ID' => 101], '101', 'x', 'rest', 'now', $url_of));
        $this->assertNull(tuleva_disclosure_log_entry('prospectus_file', null, '', 'x', 'rest', 'now', $url_of));
        $this->assertNull(tuleva_disclosure_log_entry('prospectus_file', false, '', 'x', 'rest', 'now', $url_of));
    }

    #[Test]
    public function a_figure_is_logged_without_urls(): void
    {
        $entry = tuleva_disclosure_log_entry('fund_co2_intensity', '83.68', '84.10', 'x', 'rest', 'now', fn(int $id) => 'unused');

        $this->assertArrayNotHasKey('after_url', $entry);
        $this->assertSame(['83.68', '84.10'], [$entry['before'], $entry['after']]);
    }

    /**
     * The lock follows the writer, not the route: an editor's logged-in session reaches REST
     * with the wp-admin nonce, and WP-CLI or a cron run can act as an editor too.
     */
    #[Test]
    public function an_editor_may_not_write_on_any_route(): void
    {
        FakeDisclosureWrite::$login = 'an-editor';

        foreach (['wp-admin', 'rest', 'cron', 'other'] as $route) {
            $this->assertFalse(tuleva_disclosure_may_write($route), $route);
        }
    }

    /**
     * Any user can add an application password to their own profile, so signing with one is
     * not enough: the account has to hold the writer capability too.
     */
    #[Test]
    public function an_editor_s_own_application_password_does_not_open_the_fields(): void
    {
        FakeDisclosureWrite::writerSigning('an-editor');
        FakeDisclosureWrite::$writer = false;

        $this->assertFalse(tuleva_disclosure_may_write('rest'));
    }

    /**
     * The writers are services that sign each request. A browser logged in as one of them, or
     * a script running in that browser, is not one of them; nor is the edit screen.
     */
    #[Test]
    public function a_writer_account_writes_over_rest_with_its_application_password_and_nowhere_else(): void
    {
        FakeDisclosureWrite::writerSigning('agent-gateway');

        $this->assertTrue(tuleva_disclosure_may_write('rest'));
        $this->assertFalse(tuleva_disclosure_may_write('wp-admin'));
        $this->assertFalse(tuleva_disclosure_may_write('other'));

        FakeDisclosureWrite::$application_password = null;
        $this->assertFalse(tuleva_disclosure_may_write('rest'));
    }

    #[Test]
    public function an_administrator_may_write_on_any_route(): void
    {
        FakeDisclosureWrite::$administrator = true;
        FakeDisclosureWrite::$login = 'an-administrator';

        foreach (['wp-admin', 'rest', 'cron', 'other'] as $route) {
            $this->assertTrue(tuleva_disclosure_may_write($route), $route);
        }
    }

    /**
     * The upcoming-document switch writes while its own hook runs, whoever's request WP-Cron
     * runs in (ALTERNATE_WP_CRON runs it inside a visitor's or an editor's). Any other cron
     * run is refused like any other write.
     */
    #[Test]
    public function wp_cron_writes_only_while_the_upcoming_document_switch_runs(): void
    {
        FakeDisclosureWrite::$doing_action = TULEVA_DISCLOSURE_SWITCH_HOOK;
        $this->assertTrue(tuleva_disclosure_may_write('cron'));

        FakeDisclosureWrite::$login = 'an-editor';
        $this->assertTrue(tuleva_disclosure_may_write('cron'));

        FakeDisclosureWrite::$doing_action = 'some_plugin_s_event';
        $this->assertFalse(tuleva_disclosure_may_write('cron'));

        FakeDisclosureWrite::$login = '';
        $this->assertFalse(tuleva_disclosure_may_write('cron'));
    }

    #[Test]
    public function the_hook_is_the_one_the_upcoming_document_switch_is_scheduled_on(): void
    {
        $this->assertSame('tuleva_promote_due_documents', TULEVA_DISCLOSURE_SWITCH_HOOK);
    }

    /**
     * A writer nobody can name (no user, outside WP-Cron and REST: WP-CLI without --user, say)
     * is refused rather than let through.
     */
    #[Test]
    public function a_write_from_nobody_known_is_refused(): void
    {
        $this->assertFalse(tuleva_disclosure_may_write('other'));
        $this->assertFalse(tuleva_disclosure_may_write('rest'));
        $this->assertFalse(tuleva_disclosure_may_write('cron'));
    }

    /**
     * The field is not on an editor's screen, so a value for it arriving from wp-admin was
     * posted by hand. ACF is stopped before it does anything with it: the field type's own
     * update_value would attach the posted document to the page, and the meta write would
     * leave a row behind.
     */
    #[Test]
    public function an_editor_s_value_from_wp_admin_stops_acf_before_anything_is_saved(): void
    {
        FakeDisclosureWrite::$in_admin = true;
        FakeDisclosureWrite::$login = 'an-editor';

        $this->assertFalse(tuleva_disclosure_pre_update(null, 102, self::PAGE, ['name' => 'prospectus_file']));
        $this->assertFalse(tuleva_disclosure_pre_update(null, 301, self::PAGE, ['name' => 'investment_report_file']));
        $this->assertSame([], FakeDisclosureWrite::$meta);
    }

    #[Test]
    public function acf_goes_on_with_a_value_the_writer_may_set_and_with_any_other_field(): void
    {
        FakeDisclosureWrite::$in_admin = true;
        FakeDisclosureWrite::$login = 'an-editor';

        $this->assertNull(tuleva_disclosure_pre_update(null, 'x', self::PAGE, ['name' => 'hero_title']));
        $this->assertNull(tuleva_disclosure_pre_update(null, 102, 'options', ['name' => 'prospectus_file']));
        $this->assertSame('decided', tuleva_disclosure_pre_update('decided', 102, self::PAGE, ['name' => 'prospectus_file']));

        FakeDisclosureWrite::$administrator = true;
        $this->assertNull(tuleva_disclosure_pre_update(null, 102, self::PAGE, ['name' => 'prospectus_file']));
    }

    /**
     * Should a refused write reach update_value anyway, the stored value is kept, and where
     * nothing was stored ACF is told to delete rather than write an empty row.
     */
    #[Test]
    public function update_value_keeps_the_stored_value_of_a_refused_write(): void
    {
        FakeDisclosureWrite::$in_admin = true;
        FakeDisclosureWrite::$login = 'an-editor';
        FakeDisclosureWrite::$meta[self::PAGE]['prospectus_file'] = ['101'];

        $this->assertSame('101', tuleva_disclosure_on_update(102, self::PAGE, ['name' => 'prospectus_file']));
        $this->assertNull(tuleva_disclosure_on_update(301, self::PAGE, ['name' => 'investment_report_file']));
        $this->assertSame([], get_post_meta(self::PAGE, TULEVA_DISCLOSURE_LOG));
    }

    #[Test]
    public function an_administrator_s_write_from_wp_admin_is_taken_and_logged(): void
    {
        FakeDisclosureWrite::$administrator = true;
        FakeDisclosureWrite::$login = 'an-administrator';
        FakeDisclosureWrite::$in_admin = true;

        $this->assertSame(102, $this->acfWrites(102, 'prospectus_file'));
        $this->assertSame(['an-administrator', 'wp-admin'], $this->onlyEntry(['who', 'route']));
    }

    /**
     * The log follows the meta write, so a value ACF never stores is never logged, and one
     * it stores is logged with what it replaced.
     */
    #[Test]
    public function a_change_is_logged_once_its_value_is_stored_and_not_before(): void
    {
        FakeDisclosureWrite::$administrator = true;
        FakeDisclosureWrite::$login = 'an-administrator';
        FakeDisclosureWrite::$meta[self::PAGE]['prospectus_file'] = ['101'];

        tuleva_disclosure_on_update(102, self::PAGE, ['name' => 'prospectus_file']);
        $this->assertSame([], get_post_meta(self::PAGE, TULEVA_DISCLOSURE_LOG));

        tuleva_disclosure_on_meta_write(7, self::PAGE, 'prospectus_file', 102);
        $this->assertSame(['101', '102'], $this->onlyEntry(['before', 'after']));
    }

    #[Test]
    public function a_document_acf_deletes_is_logged_when_its_row_goes(): void
    {
        FakeDisclosureWrite::$administrator = true;
        FakeDisclosureWrite::$login = 'an-administrator';
        FakeDisclosureWrite::$meta[self::PAGE]['prospectus_file'] = ['101'];

        $this->acfWrites(null, 'prospectus_file');

        $this->assertSame(['101', ''], $this->onlyEntry(['before', 'after']));
    }

    /**
     * WPML's copy to a translation, WP-CLI, a revision's copy: written past ACF, not logged
     * here.
     */
    #[Test]
    public function a_meta_write_acf_did_not_make_is_not_logged(): void
    {
        FakeDisclosureWrite::$administrator = true;

        tuleva_disclosure_on_meta_write(7, self::PAGE, 'prospectus_file', 102);

        $this->assertSame([], get_post_meta(self::PAGE, TULEVA_DISCLOSURE_LOG));
    }

    /**
     * A document must not change unrecorded: when the log cannot be written, the value is put
     * back and the PHP error log says so.
     */
    #[Test]
    public function a_change_the_log_cannot_hold_is_put_back_and_reported(): void
    {
        FakeDisclosureWrite::$administrator = true;
        FakeDisclosureWrite::$login = 'an-administrator';
        FakeDisclosureWrite::$meta[self::PAGE]['prospectus_file'] = ['101'];
        FakeDisclosureWrite::$log_fails = true;

        $errors = $this->errorLog(fn() => $this->acfWrites(102, 'prospectus_file'));

        $this->assertSame('101', get_post_meta(self::PAGE, 'prospectus_file', true));
        $this->assertStringContainsString('prospectus_file on page ' . self::PAGE, $errors);
    }

    /**
     * Putting back touches only a value that is still the one written: one written since,
     * by another request, stays. A document that was new is taken away again.
     */
    #[Test]
    public function putting_back_leaves_a_value_written_since_alone(): void
    {
        FakeDisclosureWrite::$meta[self::PAGE]['prospectus_file'] = ['202'];
        FakeDisclosureWrite::$meta[self::PAGE]['terms_file'] = ['202'];
        FakeDisclosureWrite::$meta[self::PAGE]['model_portfolio_file'] = ['301'];

        tuleva_disclosure_put_back(self::PAGE, 'prospectus_file', '101', 102);
        tuleva_disclosure_put_back(self::PAGE, 'terms_file', '', 102);
        tuleva_disclosure_put_back(self::PAGE, 'model_portfolio_file', '', 301);

        $this->assertSame('202', get_post_meta(self::PAGE, 'prospectus_file', true));
        $this->assertSame('202', get_post_meta(self::PAGE, 'terms_file', true));
        $this->assertSame([], get_post_meta(self::PAGE, 'model_portfolio_file'));
    }

    /**
     * WordPress defines REST_REQUEST while it serves a REST request. A constant cannot be
     * undone, so this runs in a process of its own.
     */
    #[Test]
    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
    public function the_report_job_s_write_over_rest_is_taken_and_logged(): void
    {
        define('REST_REQUEST', true);
        FakeDisclosureWrite::writerSigning('reports');
        FakeAttachments::$urls[301] = 'https://tuleva.ee/wp-content/uploads/2026/12/Aruanne-2026-11.pdf';

        $this->assertSame(301, $this->acfWrites(301, 'investment_report_file'));

        $this->assertSame(
            ['reports', 'rest', '', '301', 'https://tuleva.ee/wp-content/uploads/2026/12/Aruanne-2026-11.pdf'],
            $this->onlyEntry(['who', 'route', 'before', 'after', 'after_url'])
        );
    }

    #[Test]
    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
    public function an_editor_s_session_over_rest_is_refused_like_the_edit_screen(): void
    {
        define('REST_REQUEST', true);
        FakeDisclosureWrite::$login = 'an-editor';

        $this->assertSame('rest', tuleva_disclosure_write_route());
        $this->assertFalse(tuleva_disclosure_pre_update(null, 102, self::PAGE, ['name' => 'prospectus_file']));
    }

    /**
     * Refused before anything is saved, and loudly: ACF would answer 200 with the old value
     * kept, and an unattended writer would log a success.
     */
    #[Test]
    public function a_rest_write_to_a_field_the_writer_may_not_set_is_answered_403(): void
    {
        FakeDisclosureWrite::$login = 'an-editor';
        $request = new FakeRestRequest('POST', ['acf' => ['hero_title' => 'x', 'prospectus_file' => 102, 'fund_co2_intensity' => '84.10']]);

        $refused = tuleva_disclosure_rest_guard(null, [], $request);

        $this->assertInstanceOf(WP_Error::class, $refused);
        $this->assertSame(['tuleva_fund_document_locked', ['status' => 403]], [$refused->get_error_code(), $refused->get_error_data()]);
        $this->assertStringEndsWith(': prospectus_file, fund_co2_intensity', $refused->get_error_message());
    }

    /**
     * ACF takes a field's key in place of its name, so a key is refused as its name is.
     */
    #[Test]
    public function a_document_field_named_by_its_acf_key_is_refused_too(): void
    {
        FakeDisclosureWrite::$login = 'an-editor';
        $request = new FakeRestRequest('PATCH', ['acf' => ['field_fund_savings_prospectus' => 102, 'field_fund_disclosure_stocks_fund_co2_intensity' => '1']]);

        $refused = tuleva_disclosure_rest_guard(null, [], $request);

        $this->assertStringEndsWith(': prospectus_file, fund_co2_intensity', $refused->get_error_message());
    }

    /**
     * Without a user, WordPress's own permission check answers (401), so a missing credential
     * is not reported as a missing role.
     */
    #[Test]
    public function a_request_without_a_user_is_left_to_wordpress_s_own_checks(): void
    {
        $this->assertNull(tuleva_disclosure_rest_guard(null, [], new FakeRestRequest('POST', ['acf' => ['prospectus_file' => 102]])));
    }

    #[Test]
    public function a_rest_request_the_lock_does_not_concern_goes_on(): void
    {
        FakeDisclosureWrite::$login = 'an-editor';
        $earlier = new WP_Error('rest_no_route', 'No route', ['status' => 404]);

        $this->assertNull(tuleva_disclosure_rest_guard(null, [], new FakeRestRequest('POST', ['acf' => ['hero_title' => 'x']])));
        $this->assertNull(tuleva_disclosure_rest_guard(null, [], new FakeRestRequest('POST', ['title' => 'x', 'acf' => 'x'])));
        $this->assertNull(tuleva_disclosure_rest_guard(null, [], new FakeRestRequest('GET', ['acf' => ['prospectus_file' => 102]])));
        $this->assertSame($earlier, tuleva_disclosure_rest_guard($earlier, [], new FakeRestRequest('POST', ['acf' => ['prospectus_file' => 102]])));

        FakeDisclosureWrite::writerSigning('agent-gateway');
        $this->assertNull(tuleva_disclosure_rest_guard(null, [], new FakeRestRequest('POST', ['acf' => ['prospectus_file' => 102]])));
    }

    /**
     * What the REST writers' calls need: read pages and media, upload a PDF, update a page
     * someone else wrote, published or, the shared documents page, private. And
     * unfiltered_html, which every Editor has: without it WordPress runs the stored page body
     * and title through kses on each update, even one that sets only fields, and strips what
     * it does not allow.
     */
    #[Test]
    public function the_writer_role_may_update_pages_private_ones_too_upload_media_and_write_the_fields(): void
    {
        tuleva_register_disclosure_writer_role();

        $this->assertSame(
            ['name' => 'Fund document writer', 'capabilities' => [
                'read' => true,
                'upload_files' => true,
                'edit_pages' => true,
                'edit_others_pages' => true,
                'edit_published_pages' => true,
                'read_private_pages' => true,
                'edit_private_pages' => true,
                'unfiltered_html' => true,
                TULEVA_DISCLOSURE_WRITER => true,
            ]],
            FakeRoles::$roles['fund_document_writer']
        );
    }

    #[Test]
    public function the_writer_role_is_added_once_and_not_rewritten_while_it_is_right(): void
    {
        tuleva_register_disclosure_writer_role();
        FakeRoles::$roles['fund_document_writer']['capabilities'] = array_reverse(tuleva_disclosure_writer_capabilities());
        tuleva_register_disclosure_writer_role();

        $this->assertSame(1, FakeRoles::$added);
        $this->assertSame([], FakeOptions::$options);
    }

    /**
     * A role stored with other capabilities, such as one cloned from Editor or one that got
     * only the writer capability because Editor was missing, is put right on the next request,
     * in one write of the roles option.
     */
    #[Test]
    public function a_writer_role_that_has_drifted_is_put_right(): void
    {
        FakeRoles::$roles['fund_document_writer'] = ['name' => 'Fund document writer', 'capabilities' => [
            TULEVA_DISCLOSURE_WRITER => true,
            'edit_pages' => false,
            'unfiltered_html' => true,
            'delete_others_pages' => true,
        ]];

        tuleva_register_disclosure_writer_role();

        $this->assertSame(['wp_user_roles'], array_keys(FakeOptions::$options));
        $this->assertSame(tuleva_disclosure_writer_capabilities(), FakeOptions::$options['wp_user_roles']['fund_document_writer']['capabilities']);
        $this->assertSame('Fund document writer', FakeOptions::$options['wp_user_roles']['fund_document_writer']['name']);
        $this->assertSame(0, FakeRoles::$added);
    }

    /**
     * The upcoming-document switch writes from WP-Cron, so the write is its own whoever's
     * request it runs in.
     */
    #[Test]
    public function a_write_from_wp_cron_is_logged_as_such(): void
    {
        FakeDisclosureWrite::$in_cron = true;
        FakeDisclosureWrite::$doing_action = TULEVA_DISCLOSURE_SWITCH_HOOK;
        FakeDisclosureWrite::$meta[self::PAGE]['prospectus_file'] = ['101'];

        $this->acfWrites(102, 'prospectus_file');

        $this->assertSame(['WP-Cron', 'cron'], $this->onlyEntry(['who', 'route']));
    }

    #[Test]
    public function a_field_that_is_not_a_disclosure_is_left_alone(): void
    {
        FakeDisclosureWrite::$in_admin = true;

        $this->assertSame('x', tuleva_disclosure_on_update('x', self::PAGE, ['name' => 'hero_title']));
        $this->assertSame([], FakeDisclosureWrite::$meta);
    }

    /**
     * ACF renders a field under its form input's name and keeps the field's own in `_name`.
     */
    #[Test]
    public function wp_admin_hides_a_document_field_from_everyone_but_administrators(): void
    {
        $prospectus = ['name' => 'acf[field_fund_savings_prospectus]', '_name' => 'prospectus_file'];
        $report = ['name' => 'acf[field_fund_savings_investment_report]', '_name' => 'investment_report_file'];
        $hero = ['name' => 'acf[field_hero_title]', '_name' => 'hero_title'];
        FakeDisclosureWrite::$in_admin = true;

        $this->assertFalse(tuleva_disclosure_prepare_field($prospectus));
        $this->assertFalse(tuleva_disclosure_prepare_field($report));
        $this->assertSame($hero, tuleva_disclosure_prepare_field($hero));

        FakeDisclosureWrite::$administrator = true;
        $this->assertSame($prospectus, tuleva_disclosure_prepare_field($prospectus));
        $this->assertSame($report, tuleva_disclosure_prepare_field($report));
    }

    #[Test]
    public function wordpress_s_own_custom_fields_form_cannot_reach_a_disclosure(): void
    {
        $this->assertTrue(tuleva_disclosure_is_protected_meta(false, 'investment_report_file', 'post'));
        $this->assertTrue(tuleva_disclosure_is_protected_meta(false, 'prospectus_file', 'post'));
        $this->assertFalse(tuleva_disclosure_is_protected_meta(false, 'hero_title', 'post'));
        $this->assertFalse(tuleva_disclosure_is_protected_meta(false, 'prospectus_file', 'user'));
        $this->assertTrue(tuleva_disclosure_is_protected_meta(true, '_edit_lock', 'post'));
    }

    /**
     * A revision holds the values of the last wp-admin save, so restoring one would put back
     * documents that fund_pages or the upcoming-document switch have replaced since. For a
     * writer who may not set them, ACF's copy of the revision leaves them and their
     * references out.
     */
    #[Test]
    public function an_editor_s_revision_restore_leaves_the_documents_out_of_acf_s_copy(): void
    {
        FakeDisclosureWrite::$login = 'an-editor';
        FakeDisclosureWrite::$doing_action = 'wp_restore_post_revision';
        $revision = ['prospectus_file' => '101', '_prospectus_file' => 'field_a', 'hero_title' => 'Old', '_hero_title' => 'field_b'];

        $this->assertSame(['hero_title' => 'Old', '_hero_title' => 'field_b'], tuleva_disclosures_out_of_restore($revision, 99));
    }

    #[Test]
    public function acf_s_meta_is_left_whole_outside_a_restore_and_for_an_administrator(): void
    {
        FakeDisclosureWrite::$login = 'an-editor';
        $revision = ['prospectus_file' => '101', '_prospectus_file' => 'field_a'];

        $this->assertSame($revision, tuleva_disclosures_out_of_restore($revision, 99));

        FakeDisclosureWrite::$doing_action = 'wp_restore_post_revision';
        FakeDisclosureWrite::$administrator = true;
        $this->assertSame($revision, tuleva_disclosures_out_of_restore($revision, 99));
    }

    /**
     * So nothing is put back after an editor's restore, and a value fund_pages writes while
     * it runs stays.
     */
    #[Test]
    public function a_document_written_during_an_editor_s_restore_stays(): void
    {
        FakeDisclosureWrite::$login = 'an-editor';
        FakeDisclosureWrite::$meta[self::PAGE]['prospectus_file'] = ['101'];

        tuleva_disclosures_before_restore(self::PAGE);
        update_post_meta(self::PAGE, 'prospectus_file', '202');
        tuleva_disclosures_after_restore(self::PAGE);

        $this->assertSame('202', get_post_meta(self::PAGE, 'prospectus_file', true));
        $this->assertSame([], get_post_meta(self::PAGE, TULEVA_DISCLOSURE_LOG));
    }

    #[Test]
    public function an_administrator_s_revision_restore_is_logged(): void
    {
        FakeDisclosureWrite::$administrator = true;
        FakeDisclosureWrite::$login = 'an-administrator';
        FakeDisclosureWrite::$meta[self::PAGE]['prospectus_file'] = ['202'];

        tuleva_disclosures_before_restore(self::PAGE);
        update_post_meta(self::PAGE, 'prospectus_file', '101');
        tuleva_disclosures_after_restore(self::PAGE);

        $this->assertSame('101', get_post_meta(self::PAGE, 'prospectus_file', true));
        $this->assertSame(['an-administrator', 'revision', '202', '101'], $this->onlyEntry(['who', 'route', 'before', 'after']));
    }

    #[Test]
    public function an_administrator_s_restore_the_log_cannot_hold_is_put_back(): void
    {
        FakeDisclosureWrite::$administrator = true;
        FakeDisclosureWrite::$login = 'an-administrator';
        FakeDisclosureWrite::$meta[self::PAGE]['prospectus_file'] = ['202'];
        FakeDisclosureWrite::$log_fails = true;

        $errors = $this->errorLog(function (): void {
            tuleva_disclosures_before_restore(self::PAGE);
            update_post_meta(self::PAGE, 'prospectus_file', '101');
            tuleva_disclosures_after_restore(self::PAGE);
        });

        $this->assertSame('202', get_post_meta(self::PAGE, 'prospectus_file', true));
        $this->assertStringContainsString('prospectus_file on page ' . self::PAGE, $errors);
    }

    #[Test]
    public function the_documents_box_lists_changes_newest_first(): void
    {
        $html = tuleva_disclosure_panel([], [
            ['at' => '2026-11-01T08:00:00Z', 'who' => 'older-writer', 'route' => 'rest', 'field' => 'terms_file', 'before' => '', 'after' => '7', 'after_url' => 'https://tuleva.ee/t.pdf'],
            ['at' => '2026-12-01T08:00:00Z', 'who' => 'newer-writer', 'route' => 'cron', 'field' => 'fund_co2_intensity', 'before' => '1', 'after' => '2'],
        ]);

        $this->assertLessThan(strpos($html, 'older-writer'), strpos($html, 'newer-writer'));
        $this->assertStringContainsString('>t.pdf</a>', $html);
    }

    #[Test]
    public function the_documents_box_escapes_what_it_shows(): void
    {
        $html = tuleva_disclosure_panel(
            ['prospectus_file' => 'https://tuleva.ee/wp-content/uploads/2026/12/Prospekt.pdf'],
            [['at' => 'now', 'who' => '<b>x</b>', 'route' => 'rest', 'field' => 'fund_co2_intensity', 'before' => '1', 'after' => '2']]
        );

        $this->assertStringContainsString('&lt;b&gt;x&lt;/b&gt;', $html);
        $this->assertStringContainsString('>Prospekt.pdf</a>', $html);
    }

    /**
     * An attachment deleted before its change was logged has no URL; its id is what is left.
     */
    #[Test]
    public function a_document_without_a_url_is_shown_by_its_attachment_id(): void
    {
        $entry = tuleva_disclosure_log_entry('prospectus_file', '101', '102', 'x', 'rest', 'now', fn(int $id) => false);

        $this->assertArrayNotHasKey('before_url', $entry);
        $this->assertStringContainsString('<td>101</td>', tuleva_disclosure_panel([], [$entry]));
    }

    /**
     * What acf_update_value does with a value: the pre filter, the update_value filters, the
     * meta write (null deletes), and the hook WordPress fires once the write lands.
     */
    private function acfWrites($value, string $name)
    {
        $field = ['name' => $name];

        if (tuleva_disclosure_pre_update(null, $value, self::PAGE, $field) !== null) {
            return 'short-circuited';
        }

        $value = tuleva_disclosure_on_update($value, self::PAGE, $field);

        if ($value === null) {
            unset(FakeDisclosureWrite::$meta[self::PAGE][$name]);
            tuleva_disclosure_on_meta_write([7], self::PAGE, $name, '');
        } else {
            FakeDisclosureWrite::$meta[self::PAGE][$name] = [$value];
            tuleva_disclosure_on_meta_write(7, self::PAGE, $name, $value);
        }

        return $value;
    }

    /**
     * What PHP's error_log() wrote while $run ran.
     */
    private function errorLog(callable $run): string
    {
        $file = tempnam(sys_get_temp_dir(), 'tuleva-error-log');
        $previous = ini_set('error_log', $file);

        try {
            $run();
        } finally {
            ini_set('error_log', (string) $previous);
        }

        $written = (string) file_get_contents($file);
        unlink($file);

        return $written;
    }

    /**
     * @param list<string> $keys
     * @return list<string>
     */
    private function onlyEntry(array $keys): array
    {
        $log = get_post_meta(self::PAGE, TULEVA_DISCLOSURE_LOG);
        $this->assertCount(1, $log);

        return array_map(fn($key) => $log[0][$key], $keys);
    }
}
