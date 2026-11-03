<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Stands in for the request a write to a disclosure field arrives in: who is logged in, what
 * they may do, which screen or route, how a REST request was signed, and the page's post meta.
 */
final class FakeDisclosureWrite
{
    public static bool $in_admin = false;
    public static bool $in_cron = false;
    public static bool $in_rest = false;
    public static bool $administrator = false;
    public static bool $writer = false;
    public static ?string $application_password = null;
    public static string $login = '';

    /** @var array<int, array<string, list<mixed>>> */
    public static array $meta = [];

    public static function reset(): void
    {
        self::$in_admin = false;
        self::$in_cron = false;
        self::$in_rest = false;
        self::$administrator = false;
        self::$writer = false;
        self::$application_password = null;
        self::$login = '';
        self::$meta = [];
    }

    /**
     * An account with the writer role, signing a REST request with its application password.
     */
    public static function writerOverRest(string $login): void
    {
        self::$in_rest = true;
        self::$writer = true;
        self::$application_password = 'a-uuid';
        self::$login = $login;
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

if (!function_exists('esc_url')) {
    function esc_url($value)
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
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

if (!function_exists('wp_is_serving_rest_request')) {
    function wp_is_serving_rest_request()
    {
        return FakeDisclosureWrite::$in_rest;
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

if (!function_exists('add_post_meta')) {
    function add_post_meta($post_id, $meta_key, $meta_value, $unique = false)
    {
        FakeDisclosureWrite::$meta[$post_id][$meta_key][] = $meta_value;

        return true;
    }
}

require_once __DIR__ . '/../support/FakeAttachments.php';
require_once __DIR__ . '/../support/FakeRoles.php';
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
     * The field is not on an editor's screen, so a value for it arriving from wp-admin was
     * posted by hand. It is not taken, and nothing changed, so nothing is logged.
     */
    #[Test]
    public function an_editor_cannot_change_a_document_from_wp_admin(): void
    {
        FakeDisclosureWrite::$in_admin = true;
        FakeDisclosureWrite::$login = 'an-editor';
        FakeDisclosureWrite::$meta[self::PAGE]['prospectus_file'] = ['101'];

        $kept = tuleva_disclosure_on_update(102, self::PAGE, ['name' => 'prospectus_file']);

        $this->assertSame('101', $kept);
        $this->assertSame([], get_post_meta(self::PAGE, TULEVA_DISCLOSURE_LOG));
    }

    /**
     * The investment report is locked like every other document: the report job publishes it
     * over REST, and which funds it publishes is the job's decision, not an edit screen's.
     */
    #[Test]
    public function an_editor_cannot_set_the_investment_report_from_wp_admin_either(): void
    {
        FakeDisclosureWrite::$in_admin = true;
        FakeDisclosureWrite::$login = 'an-editor';

        $kept = tuleva_disclosure_on_update(301, self::PAGE, ['name' => 'investment_report_file']);

        $this->assertSame('', $kept);
        $this->assertSame([], get_post_meta(self::PAGE, TULEVA_DISCLOSURE_LOG));
    }

    #[Test]
    public function the_report_job_s_write_over_rest_is_taken_and_logged(): void
    {
        FakeDisclosureWrite::writerOverRest('reports');
        FakeAttachments::$urls[301] = 'https://tuleva.ee/wp-content/uploads/2026/12/Aruanne-2026-11.pdf';

        $written = tuleva_disclosure_on_update(301, self::PAGE, ['name' => 'investment_report_file']);

        $this->assertSame(301, $written);
        $this->assertSame(
            ['reports', 'rest', '', '301', 'https://tuleva.ee/wp-content/uploads/2026/12/Aruanne-2026-11.pdf'],
            $this->onlyEntry(['who', 'route', 'before', 'after', 'after_url'])
        );
    }

    /**
     * The lock follows the writer, not the route: an editor's logged-in session can send the
     * same POST to /wp-json/wp/v2/pages with the wp-admin nonce, and it is refused as the edit
     * screen refuses it.
     */
    #[Test]
    public function an_editor_s_own_session_cannot_change_a_document_over_rest(): void
    {
        FakeDisclosureWrite::$in_rest = true;
        FakeDisclosureWrite::$login = 'an-editor';
        FakeDisclosureWrite::$meta[self::PAGE]['prospectus_file'] = ['101'];

        $kept = tuleva_disclosure_on_update(102, self::PAGE, ['name' => 'prospectus_file']);

        $this->assertSame('101', $kept);
        $this->assertSame([], get_post_meta(self::PAGE, TULEVA_DISCLOSURE_LOG));
    }

    /**
     * Any user can add an application password to their own profile, so signing with one is
     * not enough: the account has to hold the writer capability too.
     */
    #[Test]
    public function an_editor_s_own_application_password_does_not_open_the_fields_either(): void
    {
        FakeDisclosureWrite::writerOverRest('an-editor');
        FakeDisclosureWrite::$writer = false;
        FakeDisclosureWrite::$meta[self::PAGE]['prospectus_file'] = ['101'];

        $this->assertSame('101', tuleva_disclosure_on_update(102, self::PAGE, ['name' => 'prospectus_file']));
        $this->assertSame([], get_post_meta(self::PAGE, TULEVA_DISCLOSURE_LOG));
    }

    /**
     * The writers are services that sign each request. A browser logged in as one of them, or
     * a script running in that browser, is not one of them.
     */
    #[Test]
    public function a_writer_account_s_logged_in_session_is_refused_without_its_application_password(): void
    {
        FakeDisclosureWrite::writerOverRest('agent-gateway');
        FakeDisclosureWrite::$application_password = null;
        FakeDisclosureWrite::$meta[self::PAGE]['prospectus_file'] = ['101'];

        $this->assertSame('101', tuleva_disclosure_on_update(102, self::PAGE, ['name' => 'prospectus_file']));
        $this->assertSame([], get_post_meta(self::PAGE, TULEVA_DISCLOSURE_LOG));
    }

    #[Test]
    public function a_writer_account_cannot_change_a_document_from_wp_admin(): void
    {
        FakeDisclosureWrite::writerOverRest('agent-gateway');
        FakeDisclosureWrite::$in_rest = false;
        FakeDisclosureWrite::$in_admin = true;
        FakeDisclosureWrite::$meta[self::PAGE]['prospectus_file'] = ['101'];

        $this->assertSame('101', tuleva_disclosure_on_update(102, self::PAGE, ['name' => 'prospectus_file']));
    }

    #[Test]
    public function an_administrator_s_write_is_taken_from_wp_admin_and_over_rest(): void
    {
        FakeDisclosureWrite::$administrator = true;
        FakeDisclosureWrite::$login = 'an-administrator';
        FakeDisclosureWrite::$in_admin = true;

        $this->assertSame(102, tuleva_disclosure_on_update(102, self::PAGE, ['name' => 'prospectus_file']));

        FakeDisclosureWrite::$in_admin = false;
        FakeDisclosureWrite::$in_rest = true;
        $this->assertSame('84.10', tuleva_disclosure_on_update('84.10', self::PAGE, ['name' => 'fund_co2_intensity']));

        $this->assertSame(
            [['wp-admin', 'prospectus_file'], ['rest', 'fund_co2_intensity']],
            array_map(fn($entry) => [$entry['route'], $entry['field']], get_post_meta(self::PAGE, TULEVA_DISCLOSURE_LOG))
        );
    }

    /**
     * A writer nobody can name (no user, outside WP-Cron and REST: WP-CLI without --user, say)
     * is refused rather than let through.
     */
    #[Test]
    public function a_write_from_nobody_known_is_refused(): void
    {
        FakeDisclosureWrite::$meta[self::PAGE]['prospectus_file'] = ['101'];

        $this->assertSame('101', tuleva_disclosure_on_update(102, self::PAGE, ['name' => 'prospectus_file']));
        $this->assertSame([], get_post_meta(self::PAGE, TULEVA_DISCLOSURE_LOG));
    }

    /**
     * Holding the role is how an account is let write the fields; it can still do everything
     * an Editor can, so moving an account onto it takes nothing away.
     */
    #[Test]
    public function the_writer_role_is_an_editor_that_may_write_the_fields(): void
    {
        FakeRoles::$roles['editor'] = ['name' => 'Editor', 'capabilities' => ['edit_pages' => true, 'upload_files' => true]];

        tuleva_register_disclosure_writer_role();

        $this->assertSame(
            ['name' => 'Fund document writer', 'capabilities' => [TULEVA_DISCLOSURE_WRITER => true, 'edit_pages' => true, 'upload_files' => true]],
            FakeRoles::$roles['fund_document_writer']
        );
    }

    #[Test]
    public function the_writer_role_is_added_once_and_not_rewritten_on_every_request(): void
    {
        tuleva_register_disclosure_writer_role();
        tuleva_register_disclosure_writer_role();

        $this->assertSame(1, FakeRoles::$added);
    }

    /**
     * The upcoming-document switch writes from WP-Cron with nobody logged in.
     */
    #[Test]
    public function a_write_from_wp_cron_is_logged_as_such(): void
    {
        FakeDisclosureWrite::$in_cron = true;
        FakeDisclosureWrite::$meta[self::PAGE]['prospectus_file'] = ['101'];

        tuleva_disclosure_on_update(102, self::PAGE, ['name' => 'prospectus_file']);

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
     * documents that fund_pages or the upcoming-document switch have replaced since.
     */
    #[Test]
    public function restoring_a_revision_does_not_change_a_document_for_an_editor(): void
    {
        FakeDisclosureWrite::$meta[self::PAGE]['prospectus_file'] = ['202'];

        tuleva_disclosures_before_restore(self::PAGE);
        update_post_meta(self::PAGE, 'prospectus_file', '101');
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
