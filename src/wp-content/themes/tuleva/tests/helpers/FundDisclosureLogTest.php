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

    /** @var array<int, int> revision or autosave id => the page it belongs to */
    public static array $revisions = [];

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
        self::$revisions = [];
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

if (!function_exists('update_post_meta')) {
    function update_post_meta($post_id, $meta_key, $meta_value, $prev_value = '')
    {
        FakeDisclosureWrite::$meta[$post_id][$meta_key] = [$meta_value];

        return true;
    }
}

/**
 * As WordPress's: backslashes come off strings, deep in arrays too.
 */
if (!function_exists('wp_unslash')) {
    function wp_unslash($value)
    {
        return is_array($value) ? array_map('wp_unslash', $value) : (is_string($value) ? stripslashes($value) : $value);
    }
}

if (!function_exists('wp_is_post_revision')) {
    function wp_is_post_revision($post)
    {
        return FakeDisclosureWrite::$revisions[(int) $post] ?? false;
    }
}

/**
 * As WordPress's, it writes a revision's meta to the page the revision belongs to.
 */
if (!function_exists('add_post_meta')) {
    function add_post_meta($post_id, $meta_key, $meta_value, $unique = false)
    {
        $post_id = wp_is_post_revision($post_id) ?: $post_id;

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
    private const ENGLISH_PAGE = 17534;

    protected function setUp(): void
    {
        FakeDisclosureWrite::reset();
        FakeAttachments::reset();
        FakeRoles::reset();
        FakeOptions::reset();
        $pending = &tuleva_disclosure_writes_pending();
        $pending = [];
        $restoring = &tuleva_disclosure_restore_target();
        $restoring = null;
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

    /**
     * ACF hands its filters the object id as given and decodes it only when it writes, so a
     * page can arrive as 17533, '17533' or 'post_17533' (attachment_ and menu_item_ name posts
     * too). Without ACF loaded, the id is read the way ACF reads it.
     */
    #[Test]
    public function an_acf_object_id_is_read_as_the_post_it_names_and_nothing_else(): void
    {
        foreach ([17533, '17533', 'post_17533', 'attachment_17533', 'menu_item_17533'] as $id) {
            $this->assertSame(17533, tuleva_disclosure_post_id($id), (string) $id);
        }

        foreach (['term_5', 'menu_5', 'user_3', 'comment_4', 'options', 'option_x', 'block_abc', 'post_', 'post_x', 0, '0', null] as $id) {
            $this->assertNull(tuleva_disclosure_post_id($id), var_export($id, true));
        }
    }

    /**
     * update_field('prospectus_file', …, 'post_17533'), from WP-CLI say, reaches the same
     * page's meta, so it meets the same lock.
     */
    #[Test]
    public function an_editor_s_write_by_the_post_id_form_is_refused(): void
    {
        FakeDisclosureWrite::$login = 'an-editor';
        FakeDisclosureWrite::$meta[self::PAGE]['prospectus_file'] = ['101'];

        $this->assertFalse(tuleva_disclosure_pre_update(null, 102, 'post_' . self::PAGE, ['name' => 'prospectus_file']));
        $this->assertSame('101', tuleva_disclosure_on_update(102, 'post_' . self::PAGE, ['name' => 'prospectus_file']));
    }

    #[Test]
    public function an_administrator_s_write_by_the_post_id_form_is_logged_against_the_page(): void
    {
        FakeDisclosureWrite::$administrator = true;
        FakeDisclosureWrite::$login = 'an-administrator';
        FakeDisclosureWrite::$meta[self::PAGE]['prospectus_file'] = ['101'];

        $this->acfWrites(102, 'prospectus_file', 'post_' . self::PAGE);

        $this->assertSame(['an-administrator', 'prospectus_file', '101', '102'], $this->onlyEntry(['who', 'field', 'before', 'after']));
    }

    #[Test]
    public function a_write_to_a_term_is_left_alone(): void
    {
        FakeDisclosureWrite::$login = 'an-editor';

        $this->assertNull(tuleva_disclosure_pre_update(null, 102, 'term_5', ['name' => 'prospectus_file']));
        $this->assertSame(102, tuleva_disclosure_on_update(102, 'term_5', ['name' => 'prospectus_file']));

        FakeDisclosureWrite::$administrator = true;
        tuleva_disclosure_note_write(null, 'term_5', 'prospectus_file', 102, false);
        tuleva_disclosure_on_meta_write(7, 5, 'prospectus_file', 102);
        $this->assertSame([], FakeDisclosureWrite::$meta);
    }

    /**
     * With ACF loaded, its own decoding decides, acf/decode_post_id filter and all. Defining
     * a function cannot be undone, so this runs in a process of its own.
     */
    #[Test]
    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
    public function with_acf_loaded_its_own_decoding_of_an_object_id_decides(): void
    {
        function acf_decode_post_id($post_id = 0)
        {
            return $post_id === 'custom_17533' ? ['type' => 'post', 'id' => '17533'] : ['type' => 'option', 'id' => $post_id];
        }

        $this->assertSame(17533, tuleva_disclosure_post_id('custom_17533'));
        $this->assertNull(tuleva_disclosure_post_id('post_17533'));
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

        tuleva_disclosure_note_write(null, self::PAGE, 'prospectus_file', 102, false);
        $this->assertSame([], get_post_meta(self::PAGE, TULEVA_DISCLOSURE_LOG));

        tuleva_disclosure_on_meta_write(7, self::PAGE, 'prospectus_file', 102);
        $this->assertSame(['101', '102'], $this->onlyEntry(['before', 'after']));
    }

    /**
     * A note is for the one write ACF is making. When WordPress does not store it (the value
     * is unchanged, a filter short-circuits it, the query fails), ACF's write of the field's
     * reference row ends the note, so a later write to the field in the same request is not
     * logged with this one's value before and route.
     */
    #[Test]
    public function a_note_ends_with_acf_s_write_of_that_field_whether_or_not_it_was_stored(): void
    {
        FakeDisclosureWrite::$administrator = true;
        FakeDisclosureWrite::$login = 'an-administrator';
        FakeDisclosureWrite::$meta[self::PAGE]['prospectus_file'] = ['101'];

        tuleva_disclosure_note_write(null, self::PAGE, 'prospectus_file', 102, false);
        tuleva_disclosure_note_write(null, self::PAGE, 'prospectus_file', 'field_fund_savings_prospectus', true);
        tuleva_disclosure_on_meta_write(8, self::PAGE, 'prospectus_file', 102);

        $this->assertSame([], get_post_meta(self::PAGE, TULEVA_DISCLOSURE_LOG));
    }

    #[Test]
    public function a_note_applies_only_to_the_value_acf_is_writing(): void
    {
        FakeDisclosureWrite::$administrator = true;
        FakeDisclosureWrite::$login = 'an-administrator';
        FakeDisclosureWrite::$meta[self::PAGE]['prospectus_file'] = ['101'];

        tuleva_disclosure_note_write(null, self::PAGE, 'prospectus_file', 102, false);
        tuleva_disclosure_on_meta_write(8, self::PAGE, 'prospectus_file', 103);
        $this->assertSame([], get_post_meta(self::PAGE, TULEVA_DISCLOSURE_LOG));

        tuleva_disclosure_on_meta_write(8, self::PAGE, 'prospectus_file', '102');
        $this->assertSame(['101', '102'], $this->onlyEntry(['before', 'after']));
    }

    /**
     * A preview's or an autosave's values are written to a revision, and a log entry added to
     * a revision lands on the page it belongs to, as if the page had changed. The lock still
     * applies to a revision.
     */
    #[Test]
    public function a_write_to_a_revision_is_not_logged_on_the_page(): void
    {
        FakeDisclosureWrite::$administrator = true;
        FakeDisclosureWrite::$login = 'an-administrator';
        FakeDisclosureWrite::$revisions[90001] = self::PAGE;

        $this->acfWrites(102, 'prospectus_file', 90001);

        $this->assertSame([], get_post_meta(self::PAGE, TULEVA_DISCLOSURE_LOG));
        $this->assertSame([], get_post_meta(90001, TULEVA_DISCLOSURE_LOG));

        FakeDisclosureWrite::$administrator = false;
        FakeDisclosureWrite::$login = 'an-editor';
        $this->assertFalse(tuleva_disclosure_pre_update(null, 103, 90001, ['name' => 'prospectus_file']));
    }

    /**
     * The value is already stored when the log is written, inside WordPress's and ACF's own
     * writes, so a failed log write changes nothing else: the PHP error log gets one line
     * saying which page, field, values and route.
     */
    #[Test]
    public function a_failed_log_write_leaves_the_value_as_written_and_is_reported(): void
    {
        FakeDisclosureWrite::$administrator = true;
        FakeDisclosureWrite::$login = 'an-administrator';
        FakeDisclosureWrite::$in_admin = true;
        FakeDisclosureWrite::$meta[self::PAGE]['prospectus_file'] = ['101'];
        FakeDisclosureWrite::$log_fails = true;

        $errors = $this->errorLog(fn() => $this->acfWrites(102, 'prospectus_file'));

        $this->assertSame(102, get_post_meta(self::PAGE, 'prospectus_file', true));
        $this->assertStringContainsString('page ' . self::PAGE . ': prospectus_file 101 -> 102 (wp-admin)', $errors);
        $this->assertSame(1, substr_count(trim($errors), "\n") + 1);
    }

    #[Test]
    public function a_failed_log_write_leaves_an_administrator_s_restore_in_place(): void
    {
        FakeDisclosureWrite::$administrator = true;
        FakeDisclosureWrite::$login = 'an-administrator';
        FakeDisclosureWrite::$meta[self::PAGE]['prospectus_file'] = ['202'];
        FakeDisclosureWrite::$log_fails = true;

        $this->restoreBegins(self::PAGE);

        $errors = $this->errorLog(fn() => $this->restoreCopies(self::PAGE, 'prospectus_file', '101'));

        $this->assertSame('101', get_post_meta(self::PAGE, 'prospectus_file', true));
        $this->assertStringContainsString('page ' . self::PAGE . ': prospectus_file 202 -> 101 (revision)', $errors);
    }

    /**
     * delete_field() and ACF's other deletions skip acf/pre_update_value, so the lock answers
     * ACF's pre-delete filter too, which ACF asks for the value and then for its reference
     * row: refused, neither goes.
     */
    #[Test]
    public function an_unauthorised_delete_field_leaves_the_value_and_its_reference(): void
    {
        FakeDisclosureWrite::$login = 'an-editor';
        FakeDisclosureWrite::$meta[self::PAGE]['prospectus_file'] = ['101'];
        FakeDisclosureWrite::$meta[self::PAGE]['_prospectus_file'] = ['field_fund_disclosure_stocks_prospectus_file'];

        $this->acfDeletes('prospectus_file');
        $this->acfDeletes('prospectus_file', 'post_' . self::PAGE);

        $this->assertSame('101', get_post_meta(self::PAGE, 'prospectus_file', true));
        $this->assertSame('field_fund_disclosure_stocks_prospectus_file', get_post_meta(self::PAGE, '_prospectus_file', true));
        $this->assertSame([], get_post_meta(self::PAGE, TULEVA_DISCLOSURE_LOG));
    }

    #[Test]
    public function an_authorised_delete_field_deletes_the_value_and_its_reference_and_is_logged(): void
    {
        FakeDisclosureWrite::$administrator = true;
        FakeDisclosureWrite::$login = 'an-administrator';
        FakeDisclosureWrite::$meta[self::PAGE]['prospectus_file'] = ['101'];
        FakeDisclosureWrite::$meta[self::PAGE]['_prospectus_file'] = ['field_fund_disclosure_stocks_prospectus_file'];

        $this->acfDeletes('prospectus_file');

        $this->assertSame([], get_post_meta(self::PAGE, 'prospectus_file'));
        $this->assertSame([], get_post_meta(self::PAGE, '_prospectus_file'));
        $this->assertSame(['an-administrator', '101', ''], $this->onlyEntry(['who', 'before', 'after']));
    }

    #[Test]
    public function a_deletion_from_another_object_is_left_alone(): void
    {
        FakeDisclosureWrite::$login = 'an-editor';

        $this->assertNull(tuleva_disclosure_pre_delete(null, 'term_5', 'prospectus_file', false));
        $this->assertNull(tuleva_disclosure_pre_delete(null, self::PAGE, 'hero_title', false));
        $this->assertSame('decided', tuleva_disclosure_pre_delete('decided', self::PAGE, 'prospectus_file', false));
    }

    /**
     * wp-admin posts a value slashed, and ACF hands it on so to its metadata filter; WordPress
     * unslashes it before it stores it and before its meta hooks. An apostrophe in a URL must
     * not part the note from the write it is for.
     */
    #[Test]
    public function a_value_wp_admin_posts_slashed_is_logged_once_as_stored(): void
    {
        FakeDisclosureWrite::$administrator = true;
        FakeDisclosureWrite::$login = 'an-administrator';
        FakeDisclosureWrite::$in_admin = true;
        FakeDisclosureWrite::$meta[self::PAGE]['previous_reports_url'] = ['https://tuleva.ee/aruanded/'];

        $this->acfWrites("https://tuleva.ee/aruanded/?fond=tuk75\\'s", 'previous_reports_url');

        $stored = "https://tuleva.ee/aruanded/?fond=tuk75's";
        $this->assertSame($stored, get_post_meta(self::PAGE, 'previous_reports_url', true));
        $this->assertSame(['https://tuleva.ee/aruanded/', $stored], $this->onlyEntry(['before', 'after']));
    }

    /**
     * Posting back the value already stored, slashed, is no change.
     */
    #[Test]
    public function a_stored_value_posted_back_slashed_is_no_change(): void
    {
        FakeDisclosureWrite::$administrator = true;
        FakeDisclosureWrite::$meta[self::PAGE]['previous_reports_url'] = ["https://tuleva.ee/aruanded/?fond=tuk75's"];

        tuleva_disclosure_note_write(null, self::PAGE, 'previous_reports_url', "https://tuleva.ee/aruanded/?fond=tuk75\\'s", false);

        $this->assertSame([], tuleva_disclosure_writes_pending());
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
     * A restore's entries are the writes it made, each once WordPress has stored it. ACF's
     * copy to the latest revision is not one of the page's.
     */
    #[Test]
    public function an_administrator_s_revision_restore_is_logged_from_the_writes_it_made(): void
    {
        FakeDisclosureWrite::$administrator = true;
        FakeDisclosureWrite::$login = 'an-administrator';
        $this->restoreBegins(self::PAGE);
        FakeDisclosureWrite::$revisions[90002] = self::PAGE;
        FakeDisclosureWrite::$meta[self::PAGE]['prospectus_file'] = ['202'];

        $this->restoreCopies(self::PAGE, 'prospectus_file', '101');
        $this->restoreCopies(self::PAGE, 'terms_file', '301');
        $this->restoreCopies(self::PAGE, 'hero_title', 'Old');
        $this->restoreCopies(90002, 'prospectus_file', '101');

        $this->assertSame('101', get_post_meta(self::PAGE, 'prospectus_file', true));
        $this->assertSame(
            [
                ['an-administrator', 'revision', 'prospectus_file', '202', '101'],
                ['an-administrator', 'revision', 'terms_file', '', '301'],
            ],
            array_map(
                fn($entry) => [$entry['who'], $entry['route'], $entry['field'], $entry['before'], $entry['after']],
                get_post_meta(self::PAGE, TULEVA_DISCLOSURE_LOG)
            )
        );
    }

    /**
     * A revision with no prospectus copies none, so nothing about it is logged as the
     * restore's: the upcoming-document switch writing the prospectus meanwhile, in a request
     * of its own, is logged once, as its own.
     */
    #[Test]
    public function a_document_the_restore_did_not_write_is_not_logged_as_the_restore_s(): void
    {
        FakeDisclosureWrite::$meta[self::PAGE]['prospectus_file'] = ['101'];

        FakeDisclosureWrite::$administrator = true;
        FakeDisclosureWrite::$login = 'an-administrator';
        $this->restoreBegins(self::PAGE);
        $this->restoreCopies(self::PAGE, 'hero_title', 'Old');

        FakeDisclosureWrite::$administrator = false;
        FakeDisclosureWrite::$login = '';
        FakeDisclosureWrite::$in_cron = true;
        FakeDisclosureWrite::$doing_action = TULEVA_DISCLOSURE_SWITCH_HOOK;
        $this->acfWrites(102, 'prospectus_file');

        $this->assertSame(['WP-Cron', 'cron', '101', '102'], $this->onlyEntry(['who', 'route', 'before', 'after']));
    }

    /**
     * WPML copies the fields to the English page inside the same restore, as do other
     * plugins' writes to other posts: none of them is the restore's write to its page.
     */
    #[Test]
    public function a_write_to_another_post_during_a_restore_is_not_logged_as_the_restore_s(): void
    {
        FakeDisclosureWrite::$administrator = true;
        FakeDisclosureWrite::$login = 'an-administrator';
        FakeDisclosureWrite::$meta[self::ENGLISH_PAGE]['prospectus_file'] = ['202'];
        $this->restoreBegins(self::PAGE);

        $this->restoreCopies(self::ENGLISH_PAGE, 'prospectus_file', '101');

        $this->assertSame([], get_post_meta(self::ENGLISH_PAGE, TULEVA_DISCLOSURE_LOG));
        $this->assertSame([], get_post_meta(self::PAGE, TULEVA_DISCLOSURE_LOG));
    }

    /**
     * A restore write WordPress does not store (a failed query, say) leaves its note behind;
     * the end of the restore clears it, so a later write of the same value is not taken for
     * the restore's.
     */
    #[Test]
    public function a_restore_note_does_not_outlive_the_restore(): void
    {
        FakeDisclosureWrite::$administrator = true;
        FakeDisclosureWrite::$login = 'an-administrator';
        FakeDisclosureWrite::$meta[self::PAGE]['prospectus_file'] = ['202'];
        $this->restoreBegins(self::PAGE);

        tuleva_disclosure_note_write(null, self::PAGE, 'prospectus_file', '101', false);
        tuleva_disclosure_note_restore_update(7, self::PAGE, 'prospectus_file', '101');
        $this->restoreEnds(self::PAGE);

        tuleva_disclosure_on_meta_write(7, self::PAGE, 'prospectus_file', '101');
        $this->assertSame([], get_post_meta(self::PAGE, TULEVA_DISCLOSURE_LOG));
        $this->assertSame([], tuleva_disclosure_writes_pending());
    }

    /**
     * Before ACF 6.4 the restore copied through acf_update_metadata(), so ACF's own filter
     * notes the write first, with the request's route, and WordPress's action just before
     * the write then makes it the restore's: one entry, 'revision'.
     */
    #[Test]
    public function a_restore_through_acf_s_own_metadata_write_is_logged_once_as_the_restore_s(): void
    {
        FakeDisclosureWrite::$administrator = true;
        FakeDisclosureWrite::$login = 'an-administrator';
        FakeDisclosureWrite::$in_admin = true;
        FakeDisclosureWrite::$meta[self::PAGE]['prospectus_file'] = ['202'];
        $this->restoreBegins(self::PAGE);

        $this->restoreCopies(self::PAGE, 'prospectus_file', '101', true);
        $this->restoreCopies(self::PAGE, '_prospectus_file', 'field_fund_disclosure_stocks_prospectus_file', true);
        $this->restoreEnds(self::PAGE);

        $this->assertSame(['an-administrator', 'revision', '202', '101'], $this->onlyEntry(['who', 'route', 'before', 'after']));
    }

    #[Test]
    public function a_meta_write_outside_a_restore_is_not_taken_for_one(): void
    {
        FakeDisclosureWrite::$administrator = true;
        FakeDisclosureWrite::$meta[self::PAGE]['prospectus_file'] = ['202'];

        $this->restoreCopies(self::PAGE, 'prospectus_file', '101');

        $this->assertSame([], get_post_meta(self::PAGE, TULEVA_DISCLOSURE_LOG));
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
     * What acf_update_value does with a value: the pre filter, the update_value filters, then
     * for the value and for its reference row each, ACF's pre-metadata filter and the meta
     * write (null deletes both), with the hook WordPress fires once the value's write lands.
     * The filters get the id as given; ACF decodes it ('post_17533' is 17533) for the meta
     * write, and update_metadata writes to the id it is given, a revision too. ACF hands the
     * value on as it got it, slashed from wp-admin; WordPress unslashes it before it stores
     * it and before its hooks.
     */
    private function acfWrites($value, string $name, int|string $post_id = self::PAGE)
    {
        $field = ['name' => $name, 'key' => 'field_' . $name];
        $acf_post_id = $post_id;
        $post_id = (int) preg_replace('/^post_/', '', (string) $post_id);

        if (tuleva_disclosure_pre_update(null, $value, $acf_post_id, $field) !== null) {
            return 'short-circuited';
        }

        $value = tuleva_disclosure_on_update($value, $acf_post_id, $field);

        if ($value === null) {
            $this->acfDeletes($name, $acf_post_id);
        } else {
            tuleva_disclosure_note_write(null, $acf_post_id, $name, $value, false);
            FakeDisclosureWrite::$meta[$post_id][$name] = [wp_unslash($value)];
            tuleva_disclosure_on_meta_write(7, $post_id, $name, wp_unslash($value));
            tuleva_disclosure_note_write(null, $acf_post_id, $name, $field['key'], true);
        }

        return $value;
    }

    /**
     * What acf_delete_value does, for delete_field() and an update to null alike: for the
     * value and then for its reference row, ACF's pre-delete filters (the lock, then the note)
     * and the delete unless one answered, with the hook WordPress fires once the value's row
     * goes.
     */
    private function acfDeletes(string $name, int|string $acf_post_id = self::PAGE): void
    {
        $post_id = (int) preg_replace('/^post_/', '', (string) $acf_post_id);

        foreach ([false, true] as $hidden) {
            $pre = tuleva_disclosure_note_delete(
                tuleva_disclosure_pre_delete(null, $acf_post_id, $name, $hidden),
                $acf_post_id,
                $name,
                $hidden
            );
            $key = ($hidden ? '_' : '') . $name;

            if ($pre === null && isset(FakeDisclosureWrite::$meta[$post_id][$key])) {
                unset(FakeDisclosureWrite::$meta[$post_id][$key]);
                if (!$hidden) {
                    tuleva_disclosure_on_meta_write([7], $post_id, $name, '');
                }
            }
        }
    }

    /**
     * What ACF's revision restore does with one key of the revision's meta. From ACF 6.4 it
     * calls update_metadata straight, past its own filters; before 6.4 ($through_acf) it went
     * through acf_update_metadata(), whose acf/pre_update_metadata filter runs first, a
     * reference row under its own '_' name. WordPress does nothing for an unchanged value,
     * fires add_post_meta or update_post_meta before the write, and the -ed hook after it.
     */
    private function restoreCopies(int $post_id, string $key, $value, bool $through_acf = false): void
    {
        if ($through_acf) {
            tuleva_disclosure_note_write(null, $post_id, $key, $value, false);
        }

        $stored = FakeDisclosureWrite::$meta[$post_id][$key][0] ?? null;

        if ($stored === $value) {
            return;
        }

        if ($stored === null) {
            tuleva_disclosure_note_restore_add($post_id, $key, $value);
        } else {
            tuleva_disclosure_note_restore_update(7, $post_id, $key, $value);
        }

        FakeDisclosureWrite::$meta[$post_id][$key] = [$value];
        tuleva_disclosure_on_meta_write(7, $post_id, $key, $value);
    }

    /**
     * WordPress's wp_restore_post_revision action for a page, around ACF's copy (priority 10).
     */
    private function restoreBegins(int $post_id): void
    {
        FakeDisclosureWrite::$doing_action = 'wp_restore_post_revision';
        tuleva_disclosure_restore_begins($post_id, 90001);
    }

    private function restoreEnds(int $post_id): void
    {
        tuleva_disclosure_restore_ends($post_id, 90001);
        FakeDisclosureWrite::$doing_action = null;
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
