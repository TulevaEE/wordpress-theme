<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Stands in for the ACF and WordPress functions the document helpers call, neither of
 * which is loaded when the helper tests run outside WordPress.
 */
final class FakeFundPage
{
    public const ESTONIAN_PAGE = 35292;
    public const ENGLISH_PAGE = 36156;

    /** An English page published with no Estonian original. */
    public const UNTRANSLATED_ENGLISH_PAGE = 36157;

    /**
     * A day before any test document takes effect, so a rendered page does not change as
     * the calendar passes the dates the tests use.
     */
    public const TODAY = '2026-11-02';

    public static array $fields = [];
    public static array $english_fields = [];
    public static int $post_id = self::ESTONIAN_PAGE;
    public static string $template = '';
    public static string $today = self::TODAY;

    /** @var array<int, array<string, int>> the attachment ids ACF stores, by page */
    public static array $stored = [];

    /** @var array<string, list<int>> the pages on each template, translations included */
    public static array $pages = [];

    /** @var list<array{0: string, 1: mixed, 2: int}> field key, value, page */
    public static array $updates = [];

    /** @var array<int, array<string, mixed>> what get_field() returns on a page other than the fund page */
    public static array $page_fields = [];

    /** @var list<string> field keys whose writes ACF does not store */
    public static array $failing_writes = [];

    /** @var array<int, string> post status by page, 'publish' when not listed */
    public static array $statuses = [];

    /** @var array<string, array<string, int>> fields another request saves right after a write to a field key */
    public static array $saved_meanwhile = [];

    public static ?int $status_sent = null;

    /** @var list<string> */
    public static array $template_parts = [];

    /** what get_current_screen() returns in wp-admin, null outside it */
    public static ?object $screen = null;

    /** @var list<array{0: string, 1: string}> script handle and inline script added to it */
    public static array $inline_scripts = [];

    public static function reset(): void
    {
        self::$fields = [];
        self::$english_fields = [];
        self::$post_id = self::ESTONIAN_PAGE;
        self::$template = '';
        self::$today = self::TODAY;
        self::$stored = [];
        self::$pages = [];
        self::$updates = [];
        self::$page_fields = [];
        self::$failing_writes = [];
        self::$statuses = [];
        self::$saved_meanwhile = [];
        self::$status_sent = null;
        self::$template_parts = [];
        self::$screen = null;
        self::$inline_scripts = [];
    }
}

function get_field($name, $post_id = false, $format_value = true)
{
    if (!$format_value) {
        return FakeFundPage::$stored[$post_id][$name] ?? null;
    }

    if (isset(FakeFundPage::$page_fields[$post_id])) {
        return FakeFundPage::$page_fields[$post_id][$name] ?? null;
    }

    $fields = ($post_id ?: FakeFundPage::$post_id) === FakeFundPage::ENGLISH_PAGE
        ? FakeFundPage::$english_fields
        : FakeFundPage::$fields;

    return $fields[$name] ?? null;
}

/**
 * Stores the value under the field's name, as ACF does, unless the key is set to fail.
 */
function update_field($selector, $value, $post_id = false)
{
    FakeFundPage::$updates[] = [$selector, $value, $post_id];

    if (in_array($selector, FakeFundPage::$failing_writes, true)) {
        return false;
    }

    foreach (tuleva_fund_disclosure_scope() as $template => $scope) {
        foreach (array_keys($scope) as $name) {
            if (tuleva_fund_disclosure_field($name, $template)['key'] === $selector) {
                FakeFundPage::$stored[$post_id][$name] = $value;
            }
        }
    }

    foreach (FakeFundPage::$saved_meanwhile[$selector] ?? [] as $name => $saved) {
        FakeFundPage::$stored[$post_id][$name] = $saved;
    }

    return true;
}

if (!function_exists('wp_cache_delete')) {
    function wp_cache_delete($key, $group = '')
    {
        return true;
    }
}

if (!function_exists('acf_flush_value_cache')) {
    function acf_flush_value_cache($post_id = 0, $field_name = '')
    {
    }
}

function get_posts($args = null)
{
    return array_values(array_filter(
        FakeFundPage::$pages[$args['meta_value']] ?? [],
        fn($page) => in_array(get_post_status($page), (array) ($args['post_status'] ?? 'publish'), true)
    ));
}

if (!function_exists('wp_add_inline_script')) {
    function wp_add_inline_script($handle, $data, $position = 'after')
    {
        FakeFundPage::$inline_scripts[] = [$handle, $data];

        return true;
    }
}

if (!function_exists('get_current_screen')) {
    function get_current_screen()
    {
        return FakeFundPage::$screen;
    }
}

if (!function_exists('status_header')) {
    function status_header($code, $description = '')
    {
        FakeFundPage::$status_sent = $code;
    }
}

if (!function_exists('nocache_headers')) {
    function nocache_headers()
    {
    }
}

if (!function_exists('get_template_part')) {
    function get_template_part($slug, $name = null, $args = [])
    {
        FakeFundPage::$template_parts[] = $slug;
    }
}

function get_the_ID()
{
    return FakeFundPage::$post_id;
}

/**
 * WPML's answer on production: the English page is a translation of the Estonian one. A page with
 * no Estonian original answers itself only when asked to fall back to it, and null otherwise.
 */
function apply_filters($hook, $value, ...$args)
{
    if ($hook === 'wpml_object_id' && $value === FakeFundPage::ENGLISH_PAGE && ($args[2] ?? null) === 'et') {
        return FakeFundPage::ESTONIAN_PAGE;
    }

    if ($hook === 'wpml_object_id' && $value === FakeFundPage::UNTRANSLATED_ENGLISH_PAGE && ($args[2] ?? null) === 'et') {
        return ($args[1] ?? false) ? $value : null;
    }

    if ($hook === 'tuleva_today') {
        return FakeFundPage::$today;
    }

    return $value;
}

/**
 * The rendered page's template, or a listed page's.
 */
function get_page_template_slug($post = null)
{
    if ($post === null) {
        return FakeFundPage::$template;
    }

    foreach (FakeFundPage::$pages as $template => $pages) {
        if (in_array($post, $pages, true)) {
            return $template;
        }
    }

    return '';
}

function get_post_status($post = null)
{
    return FakeFundPage::$statuses[$post] ?? 'publish';
}

/**
 * The rest of WordPress, for the tests that render a fund template rather than call a
 * helper. Guarded because ReportLinkTest stands the same ones up and PHPUnit runs both
 * in one process; the definitions match, so whichever file loads first wins harmlessly.
 */
if (!defined('TEXT_DOMAIN')) {
    define('TEXT_DOMAIN', 'tuleva');
}

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

if (!function_exists('wp_next_scheduled')) {
    function wp_next_scheduled($hook, $args = [])
    {
        return false;
    }
}

if (!function_exists('wp_schedule_event')) {
    function wp_schedule_event($timestamp, $recurrence, $hook, $args = [])
    {
        return true;
    }
}

if (!function_exists('add_shortcode')) {
    function add_shortcode($tag, $callback)
    {
    }
}

if (!function_exists('get_site_url')) {
    function get_site_url()
    {
        return 'https://tuleva.ee';
    }
}

if (!function_exists('__')) {
    function __($text, $domain = null)
    {
        return $text;
    }
}

if (!function_exists('_e')) {
    function _e($text, $domain = null)
    {
        echo $text;
    }
}

if (!function_exists('esc_html')) {
    function esc_html($value)
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('esc_html__')) {
    function esc_html__($text, $domain = null)
    {
        return htmlspecialchars((string) $text, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('esc_attr')) {
    function esc_attr($value)
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('wp_json_encode')) {
    function wp_json_encode($data, $flags = 0)
    {
        return json_encode($data, $flags);
    }
}

if (!function_exists('get_permalink')) {
    function get_permalink($post = null)
    {
        return 'https://tuleva.ee/fondid/tuleva-taiendav-kogumisfond/';
    }
}

if (!function_exists('have_posts')) {
    function have_posts()
    {
        return false;
    }
}

if (!function_exists('get_the_title')) {
    function get_the_title($post = 0)
    {
        return 'Tuleva Maailma Aktsiate Pensionifond';
    }
}

require_once __DIR__ . '/../support/FakeOptions.php';
require_once __DIR__ . '/../support/FakeAttachments.php';
require_once __DIR__ . '/../support/FakeEscaping.php';
require_once __DIR__ . '/../support/FakeWordPress.php';
require_once __DIR__ . '/../../helpers/funds-api.php';
require_once __DIR__ . '/../../helpers/fund-figures.php';
require_once __DIR__ . '/../../helpers/acf/fund-disclosures.php';
require_once __DIR__ . '/../../helpers/extras.php';
require_once __DIR__ . '/../../helpers/schema.php';

final class FundDisclosuresTest extends TestCase
{
    private const PENSION_TEMPLATES = [
        'page_fund-stocks.php',
        'page_fund-bonds.php',
        'page_fund-third.php',
    ];

    private const SAVINGS_TEMPLATE = 'page_fund-savings.php';

    private const SHARED_PAGE = 41000;

    protected function setUp(): void
    {
        FakeFundPage::reset();
        FakeOptions::reset();
        FakeWordPress::reset();
        FakeAttachments::reset();
        $this->resetSharedPageLookup();
        $this->resetRestTemplates();
    }

    private function resetRestTemplates(): void
    {
        $templates = &tuleva_rest_page_templates();
        $templates = [];
    }

    /**
     * What get_field() formats from the attachment ids the page stores, as ACF would.
     */
    private function showStoredFields(int $page): void
    {
        FakeFundPage::$page_fields[$page] = array_map(
            fn($id) => FakeAttachments::$urls[$id] ?? '',
            FakeFundPage::$stored[$page]
        );
    }

    private function resetSharedPageLookup(): void
    {
        $found = &tuleva_shared_documents_page_found();
        $found = [];
    }

    #[Test]
    public function every_scoped_document_is_defined_in_the_catalogue(): void
    {
        $catalogue = tuleva_fund_disclosure_catalogue();

        foreach (tuleva_fund_disclosure_scope() as $template => $documents) {
            foreach (array_keys($documents) as $name) {
                $this->assertArrayHasKey(
                    $name,
                    $catalogue,
                    "$template scopes '$name', which has no catalogue entry"
                );
            }
        }
    }

    #[Test]
    public function every_scoped_document_is_required_or_optional(): void
    {
        foreach (tuleva_fund_disclosure_scope() as $template => $documents) {
            foreach ($documents as $name => $requirement) {
                $this->assertContains(
                    $requirement,
                    [TULEVA_DISCLOSURE_REQUIRED, TULEVA_DISCLOSURE_OPTIONAL],
                    "$template scopes '$name' as '$requirement'"
                );
            }
        }
    }

    #[Test]
    public function generated_field_keys_are_unique_across_fund_pages(): void
    {
        $keys = [];

        foreach (array_keys(tuleva_fund_disclosure_scope()) as $template) {
            foreach (tuleva_fund_disclosure_field_group($template)['fields'] as $field) {
                $keys[] = $field['key'];
            }
        }

        $this->assertSame(
            array_values(array_unique($keys)),
            $keys,
            'Two fund pages generate the same ACF field key; ACF would register only one of them'
        );
    }

    /**
     * ACF resolves a stored value by field name and keeps a reference to the key beside
     * it. Renaming a key of a field that already holds a value leaves the value in place
     * but the reference dangling, so TKF100's keys are pinned and must not drift.
     */
    #[Test]
    public function tkf100_keeps_the_field_keys_its_values_were_stored_under(): void
    {
        $keys = [];

        foreach (tuleva_fund_disclosure_field_group(self::SAVINGS_TEMPLATE)['fields'] as $field) {
            $keys[$field['name']] = $field['key'];
        }

        $this->assertSame('field_fund_savings_prospectus', $keys['prospectus_file']);
        $this->assertSame('field_fund_savings_terms', $keys['terms_file']);
        $this->assertSame('field_fund_savings_model_portfolio', $keys['model_portfolio_file']);
        $this->assertSame('field_fund_savings_key_investor_info', $keys['key_investor_info_file']);
        $this->assertSame('field_fund_savings_nav_procedure', $keys['nav_procedure_file']);
        $this->assertSame('field_fund_savings_investor_rights', $keys['investor_rights_file']);
        $this->assertSame('field_fund_savings_investment_report', $keys['investment_report_file']);
        $this->assertSame('field_fund_savings_previous_reports_url', $keys['previous_reports_url']);
        $this->assertSame('field_fund_savings_prospectus_upcoming', $keys['prospectus_upcoming_file']);
        $this->assertSame('field_fund_savings_terms_upcoming', $keys['terms_upcoming_file']);
    }

    /**
     * Without this ACF drops the "acf" key from a REST write, returns 200, and changes
     * nothing.
     */
    #[Test]
    public function every_generated_group_is_writable_over_rest(): void
    {
        foreach (array_keys(tuleva_fund_disclosure_scope()) as $template) {
            $this->assertSame(1, tuleva_fund_disclosure_field_group($template)['show_in_rest'], $template);
        }
    }

    #[Test]
    public function the_investment_report_field_is_named_the_same_on_every_fund(): void
    {
        foreach (array_column(self::fundTemplates(), 0) as $template) {
            $this->assertNotNull(
                tuleva_fund_disclosure_requirement('investment_report_file', $template),
                "$template must carry investment_report_file — onboarding-service sets it by name on every fund"
            );
        }
    }

    #[Test]
    public function pension_funds_do_not_carry_ucits_documents(): void
    {
        foreach (self::PENSION_TEMPLATES as $template) {
            $this->assertNull(tuleva_fund_disclosure_requirement('investor_rights_file', $template));
            $this->assertNull(tuleva_fund_disclosure_requirement('nav_procedure_file', $template));
        }

        $this->assertSame(
            TULEVA_DISCLOSURE_REQUIRED,
            tuleva_fund_disclosure_requirement('investor_rights_file', self::SAVINGS_TEMPLATE)
        );
        $this->assertSame(
            TULEVA_DISCLOSURE_REQUIRED,
            tuleva_fund_disclosure_requirement('nav_procedure_file', self::SAVINGS_TEMPLATE)
        );
    }

    #[Test]
    public function tkf100_has_no_co2_intensity_and_the_pension_funds_do(): void
    {
        foreach (self::PENSION_TEMPLATES as $template) {
            $this->assertSame(
                TULEVA_DISCLOSURE_REQUIRED,
                tuleva_fund_disclosure_requirement('fund_co2_intensity', $template)
            );
        }

        $this->assertNull(
            tuleva_fund_disclosure_requirement('fund_co2_intensity', self::SAVINGS_TEMPLATE),
            'No CO2 intensity is calculated for TKF100; the figure must not be scoped to its page'
        );
    }

    /**
     * The savings template still carries the markup, so publishing a TKF100 figure one day
     * is an edit to the scope table and nothing else. Until then it renders nothing.
     */
    #[Test]
    public function the_savings_page_renders_no_co2_figure_while_it_is_out_of_scope(): void
    {
        FakeFundPage::$template = self::SAVINGS_TEMPLATE;
        FakeFundPage::$fields['fund_co2_intensity'] = '83.68';

        $this->assertSame('', tuleva_fund_disclosure_value('fund_co2_intensity', '83.68'));
    }

    #[Test]
    public function a_text_disclosure_is_not_given_file_field_settings(): void
    {
        $fields = [];

        foreach (tuleva_fund_disclosure_field_group('page_fund-stocks.php')['fields'] as $field) {
            $fields[$field['name']] = $field;
        }

        $this->assertSame('text', $fields['fund_co2_intensity']['type']);
        $this->assertArrayNotHasKey('mime_types', $fields['fund_co2_intensity']);
        $this->assertSame('pdf', $fields['prospectus_file']['mime_types']);
    }

    #[Test]
    public function a_document_outside_a_fund_scope_never_falls_back_to_another_fund_document(): void
    {
        FakeFundPage::$template = 'page_fund-stocks.php';

        $this->assertSame(
            '',
            tuleva_fund_disclosure_value('investor_rights_file', 'https://tuleva.ee/tkf100-investor-rights.pdf')
        );
    }

    #[Test]
    public function a_scoped_document_falls_back_only_while_its_field_is_empty(): void
    {
        FakeFundPage::$template = 'page_fund-stocks.php';

        $this->assertSame(
            'https://tuleva.ee/old-prospectus.pdf',
            tuleva_fund_disclosure_value('prospectus_file', 'https://tuleva.ee/old-prospectus.pdf')
        );

        FakeFundPage::$fields['prospectus_file'] = 'https://tuleva.ee/new-prospectus.pdf';

        $this->assertSame(
            'https://tuleva.ee/new-prospectus.pdf',
            tuleva_fund_disclosure_value('prospectus_file', 'https://tuleva.ee/old-prospectus.pdf')
        );
    }

    /**
     * The documents are published in Estonian and the English page links the same ones.
     * A value left on the English copy by an earlier edit must not outlive the Estonian
     * one: that is how the English TKF100 page came to list superseded documents.
     */
    #[Test]
    public function an_english_page_shows_what_its_estonian_original_publishes(): void
    {
        FakeFundPage::$template = self::SAVINGS_TEMPLATE;
        FakeFundPage::$fields['prospectus_file'] = 'https://tuleva.ee/Prospekt-alates-18.09.2026.pdf';
        FakeFundPage::$english_fields['prospectus_file'] = 'https://tuleva.ee/Prospekt-12.01.2026.pdf';
        FakeFundPage::$post_id = FakeFundPage::ENGLISH_PAGE;

        $this->assertSame(
            'https://tuleva.ee/Prospekt-alates-18.09.2026.pdf',
            tuleva_fund_disclosure_value('prospectus_file', 'https://tuleva.ee/fallback.pdf')
        );
    }

    /**
     * The page reads the Estonian original either way; copying the values keeps the
     * English page's REST response and edit screen telling the same story.
     */
    #[Test]
    public function every_disclosure_is_copied_from_the_estonian_page_to_its_translations(): void
    {
        foreach (array_keys(tuleva_fund_disclosure_scope()) as $template) {
            foreach (tuleva_fund_disclosure_field_group($template)['fields'] as $field) {
                $this->assertSame(
                    TULEVA_WPML_COPY_FROM_ORIGINAL,
                    $field['wpml_cf_preferences'] ?? null,
                    "$template {$field['name']}"
                );
            }
        }
    }

    #[Test]
    public function an_english_page_falls_back_where_its_estonian_original_has_no_value(): void
    {
        FakeFundPage::$template = self::SAVINGS_TEMPLATE;
        FakeFundPage::$english_fields['key_investor_info_file'] = 'https://tuleva.ee/Pohiteave-12.01.2026.pdf';
        FakeFundPage::$post_id = FakeFundPage::ENGLISH_PAGE;

        $this->assertSame(
            'https://tuleva.ee/Pohiteave-kehtib-alates-18.09.2026.pdf',
            tuleva_fund_disclosure_value('key_investor_info_file', 'https://tuleva.ee/Pohiteave-kehtib-alates-18.09.2026.pdf')
        );
    }

    #[Test]
    public function an_english_page_reports_the_gaps_of_its_estonian_original(): void
    {
        FakeFundPage::$template = self::SAVINGS_TEMPLATE;

        foreach (tuleva_fund_disclosure_scope()[self::SAVINGS_TEMPLATE] as $name => $requirement) {
            if ($requirement === TULEVA_DISCLOSURE_REQUIRED) {
                FakeFundPage::$english_fields[$name] = "https://tuleva.ee/$name.pdf";
            }
        }

        $this->assertSame(
            tuleva_fund_disclosures_missing(self::SAVINGS_TEMPLATE, FakeFundPage::ESTONIAN_PAGE),
            tuleva_fund_disclosures_missing(self::SAVINGS_TEMPLATE, FakeFundPage::ENGLISH_PAGE)
        );
        $this->assertContains('prospectus_file', tuleva_fund_disclosures_missing(self::SAVINGS_TEMPLATE, FakeFundPage::ENGLISH_PAGE));
    }

    #[Test]
    public function a_field_left_on_the_array_return_format_still_yields_a_url(): void
    {
        FakeFundPage::$template = self::SAVINGS_TEMPLATE;
        FakeFundPage::$fields['terms_upcoming_file'] = ['url' => 'https://tuleva.ee/upcoming-terms.pdf'];

        $this->assertSame(
            'https://tuleva.ee/upcoming-terms.pdf',
            tuleva_fund_disclosure_value('terms_upcoming_file')
        );
    }

    #[Test]
    public function a_page_that_is_not_a_fund_page_renders_no_documents(): void
    {
        FakeFundPage::$template = 'page_front.php';
        FakeFundPage::$fields['prospectus_file'] = 'https://tuleva.ee/prospectus.pdf';

        $this->assertSame('', tuleva_fund_disclosure_value('prospectus_file', 'https://tuleva.ee/fallback.pdf'));
    }

    #[Test]
    public function only_required_documents_with_no_value_count_as_gaps(): void
    {
        FakeFundPage::$template = 'page_fund-stocks.php';
        FakeFundPage::$fields = [
            'prospectus_file' => 'https://tuleva.ee/prospectus.pdf',
            'terms_file' => 'https://tuleva.ee/terms.pdf',
            'model_portfolio_file' => 'https://tuleva.ee/model.pdf',
            'investment_report_file' => 'https://tuleva.ee/report.pdf',
        ];

        // key_investor_info_file and fund_co2_intensity are required and unset; the two
        // upcoming documents and previous_reports_url are optional and unset.
        $this->assertSame(
            ['key_investor_info_file', 'fund_co2_intensity'],
            tuleva_fund_disclosures_missing()
        );
    }

    #[Test]
    public function a_fund_with_every_required_document_reports_no_gaps(): void
    {
        FakeFundPage::$template = self::SAVINGS_TEMPLATE;

        foreach (tuleva_fund_disclosure_scope()[self::SAVINGS_TEMPLATE] as $name => $requirement) {
            if ($requirement === TULEVA_DISCLOSURE_REQUIRED) {
                FakeFundPage::$fields[$name] = "https://tuleva.ee/$name.pdf";
            }
        }

        $this->assertSame([], tuleva_fund_disclosures_missing());
    }

    /**
     * @return array<string, array{string, string}>
     */
    public static function upcomingDocumentFilenames(): array
    {
        return [
            'kehtib alates' => ['TUK75-ja-TUK00-Prospekt-kehtib-alates-01.01.2027.pdf', '01.01.2027'],
            'kehtivad alates' => ['TUK00-tingimused-kehtivad-alates-01.01.2027.pdf', '01.01.2027'],
            'bare alates' => ['TKF100-Prospekt-alates-18.09.2026.pdf', '18.09.2026'],
            'media re-upload suffix' => ['TUK75-tingimused-kehtivad-alates-01.01.2027-1.pdf', '01.01.2027'],
            'full url' => [
                'https://tuleva.ee/wp-content/uploads/2026/08/Pensionifondide-vara-puhasvaartuse-maaramise-sisekord-kehtib-alates-18.09.2026.pdf',
                '18.09.2026',
            ],
        ];
    }

    #[Test]
    #[PHPUnit\Framework\Attributes\DataProvider('upcomingDocumentFilenames')]
    public function the_effective_date_comes_from_the_filename(string $url, string $expected): void
    {
        $this->assertSame($expected, tuleva_document_effective_date($url));
    }

    #[Test]
    public function a_filename_with_no_effective_date_falls_back(): void
    {
        $this->assertSame(
            '18.09.2026',
            tuleva_document_effective_date('Mudelportfell-avalikustamiseks-19.08.2026-seisuga.pdf', '18.09.2026')
        );
        $this->assertSame('', tuleva_document_effective_date(''));
    }

    /**
     * A filename is a hand-typed string, and it now drives a statement about when a
     * document takes effect. The pension funds' NAV procedure is published as
     * "...kehtib-alates-02.03.3026.pdf" — a real typo for 2026, live today.
     */
    #[Test]
    public function an_implausible_effective_date_is_refused(): void
    {
        $this->assertSame(
            '02.03.2026',
            tuleva_document_effective_date(
                'Pensionifondide-vara-puhasvaartuse-maaramise-sisekord.kehtib-alates-02.03.3026.pdf',
                '02.03.2026'
            )
        );
    }

    #[Test]
    public function a_date_that_is_not_a_date_is_refused(): void
    {
        $this->assertSame('', tuleva_document_effective_date('Prospekt-kehtib-alates-31.02.2027.pdf'));
        $this->assertSame('', tuleva_document_effective_date('Prospekt-kehtib-alates-00.13.2027.pdf'));
    }

    #[Test]
    public function template_slugs_namespace_the_generated_keys_readably(): void
    {
        $this->assertSame('stocks', tuleva_fund_template_slug('page_fund-stocks.php'));
        $this->assertSame('savings', tuleva_fund_template_slug('page_fund-savings.php'));
    }

    /**
     * The archive on pensionikeskus.ee is a different document from this month's report
     * and is declared optional, not required. It must not vanish because the required
     * one is missing — a gap in one month's publishing would otherwise take every
     * earlier month's report off the page with it.
     */
    #[Test]
    public function the_report_archive_survives_a_missing_monthly_report(): void
    {
        FakeFundPage::$fields['previous_reports_url'] = 'https://www.pensionikeskus.ee/archive/';

        $html = $this->renderSavingsPage();

        $this->assertStringContainsString('Previous reports', $html);
        $this->assertStringContainsString('https://www.pensionikeskus.ee/archive/', $html);
        $this->assertStringNotContainsString('Investment reports', $html);
    }

    #[Test]
    public function a_missing_archive_does_not_take_the_monthly_report_with_it(): void
    {
        FakeFundPage::$fields['investment_report_file']
            = 'https://tuleva.ee/wp-content/uploads/2026/09/aruanne-2026-08.pdf';

        $html = $this->renderSavingsPage();

        $this->assertStringContainsString('Investment reports (08.2026)', $html);
        $this->assertStringNotContainsString('Previous reports', $html);
    }

    #[Test]
    public function both_reports_stay_in_one_list_item_separated_by_a_break(): void
    {
        FakeFundPage::$fields['investment_report_file']
            = 'https://tuleva.ee/wp-content/uploads/2026/09/aruanne-2026-08.pdf';
        FakeFundPage::$fields['previous_reports_url'] = 'https://www.pensionikeskus.ee/archive/';

        $html = $this->renderSavingsPage();

        $this->assertMatchesRegularExpression(
            '/Investment reports \(08\.2026\).*<br>.*Previous reports/s',
            $html
        );
    }

    /**
     * The effective date is read from the filename, so a filename that does not carry one
     * leaves nothing to state. Saying "effective from" and then no date is worse than not
     * raising the question — the document is still linked either way.
     */
    #[Test]
    public function an_upcoming_document_with_no_date_in_its_filename_claims_no_date(): void
    {
        FakeFundPage::$fields['prospectus_upcoming_file']
            = 'https://tuleva.ee/wp-content/uploads/2026/09/TKF100-Prospekt-uus.pdf';

        $html = $this->renderSavingsPage();

        $this->assertStringContainsString('TKF100-Prospekt-uus.pdf', $html);
        $this->assertStringNotContainsString('effective from', $html);
    }

    #[Test]
    public function an_upcoming_document_states_the_date_its_filename_carries(): void
    {
        FakeFundPage::$fields['prospectus_upcoming_file']
            = 'https://tuleva.ee/wp-content/uploads/2026/09/TKF100-Prospekt-kehtib-alates-01.01.2027.pdf';

        $html = $this->renderSavingsPage();

        $this->assertStringContainsString('effective from 01.01.2027', $html);
    }

    /**
     * A shared "effective from" suffix can only speak for one date. Publishing the terms
     * through the field while the prospectus is still on its pre-ACF URL is enough to put
     * two dates in one list item, and the shared suffix would then state the stale
     * document's date over the fresh one.
     */
    #[Test]
    public function upcoming_documents_with_different_dates_each_state_their_own(): void
    {
        FakeFundPage::$fields['prospectus_upcoming_file']
            = 'https://tuleva.ee/wp-content/uploads/2026/12/TKF100-Prospekt-kehtib-alates-01.01.2027.pdf';
        FakeFundPage::$fields['terms_upcoming_file']
            = 'https://tuleva.ee/wp-content/uploads/2026/12/TKF100-tingimused-kehtivad-alates-15.01.2027.pdf';

        $html = $this->renderSavingsPage();

        $this->assertMatchesRegularExpression(
            '/TKF100-Prospekt-kehtib-alates-01\.01\.2027\.pdf.*?effective from 01\.01\.2027/s',
            $html
        );
        $this->assertMatchesRegularExpression(
            '/TKF100-tingimused-kehtivad-alates-15\.01\.2027\.pdf.*?effective from 15\.01\.2027/s',
            $html
        );
        $this->assertSame(2, substr_count($html, 'effective from'));
    }

    /**
     * @return array<string, array{string}>
     */
    public static function fundTemplates(): array
    {
        return [
            'TUK75' => ['page_fund-stocks.php'],
            'TUK00' => ['page_fund-bonds.php'],
            'TUV100' => ['page_fund-third.php'],
            'TKF100' => [self::SAVINGS_TEMPLATE],
        ];
    }

    /**
     * A shared suffix speaks for both documents, so an undated one beside a dated one
     * would be stated to take effect on a date nothing about it carries.
     */
    #[Test]
    #[PHPUnit\Framework\Attributes\DataProvider('fundTemplates')]
    public function an_undated_upcoming_document_does_not_take_the_other_document_date(string $template): void
    {
        FakeFundPage::$fields['prospectus_upcoming_file']
            = 'https://tuleva.ee/wp-content/uploads/2026/12/Prospekt-uus.pdf';
        FakeFundPage::$fields['terms_upcoming_file']
            = 'https://tuleva.ee/wp-content/uploads/2026/12/tingimused-kehtivad-alates-01.01.2027.pdf';

        $lines = explode('<br>', $this->renderFundPage($template));
        $prospectus_line = $this->lineContaining('Prospekt-uus.pdf', $lines);
        $terms_line = $this->lineContaining('tingimused-kehtivad-alates-01.01.2027.pdf', $lines);

        $this->assertStringContainsString('Estonian)', $prospectus_line);
        $this->assertStringNotContainsString('effective from', $prospectus_line);
        $this->assertStringNotContainsString('Terms and conditions', $prospectus_line);
        $this->assertStringContainsString('effective from 01.01.2027', $terms_line);
    }

    #[Test]
    public function upcoming_documents_that_take_effect_together_share_one_suffix(): void
    {
        FakeFundPage::$fields['prospectus_upcoming_file']
            = 'https://tuleva.ee/wp-content/uploads/2026/12/TKF100-Prospekt-kehtib-alates-01.01.2027.pdf';
        FakeFundPage::$fields['terms_upcoming_file']
            = 'https://tuleva.ee/wp-content/uploads/2026/12/TKF100-tingimused-kehtivad-alates-01.01.2027.pdf';

        $html = $this->renderSavingsPage();

        $this->assertSame(1, substr_count($html, 'effective from'));
        $this->assertStringContainsString('effective from 01.01.2027', $html);
    }

    /**
     * A disclosure is a published figure, not a flag, so "0" is a value. It is not a
     * plausible carbon intensity, but one check decides every disclosure.
     */
    #[Test]
    public function a_stored_zero_is_a_value_and_not_an_absence(): void
    {
        FakeFundPage::$template = 'page_fund-stocks.php';
        FakeFundPage::$fields['fund_co2_intensity'] = '0';

        $this->assertSame('0', tuleva_fund_disclosure_value('fund_co2_intensity', '83.68'));
        $this->assertNotContains('fund_co2_intensity', tuleva_fund_disclosures_missing());
    }

    /**
     * @return array<string, array{string, string}>
     */
    public static function fundPagesWithTheirIsin(): array
    {
        return [
            'TUK75' => ['page_fund-stocks.php', 'EE3600109435'],
            'TUK00' => ['page_fund-bonds.php', 'EE3600109443'],
            'TUV100' => ['page_fund-third.php', 'EE3600001707'],
            'TKF100' => [self::SAVINGS_TEMPLATE, 'EE0000003283'],
        ];
    }

    #[Test]
    #[PHPUnit\Framework\Attributes\DataProvider('fundPagesWithTheirIsin')]
    public function a_fund_page_shows_its_own_funds_management_fee_and_managers_units_from_the_fund_list(string $template, string $isin): void
    {
        FakeFundPage::$fields['fund_isin'] = $isin;
        FakeWordPress::respondWith(200, json_encode(array_map(fn ($listed) => [
            'isin' => $listed,
            'managementFeeRate' => $listed === $isin ? 0.00205 : 0.009,
            'fundManagerUnits' => $listed === $isin ? 202901.49203 : 1,
            'fundManagerUnitsDate' => '2026-09-30',
        ], array_column(self::fundPagesWithTheirIsin(), 1))));

        $html = $this->renderFundPage($template);

        $this->assertMatchesRegularExpression('/Management fee<\/span>\s*<span>0,205%<\/span>/', $html);
        $this->assertStringContainsString('202 901,492 units (as of 30.09.2026)', $html);
    }

    #[Test]
    public function a_fund_page_keeps_showing_the_figures_it_last_read_while_the_fund_list_is_down(): void
    {
        FakeWordPress::respondWith(200, json_encode([[
            'isin' => 'EE3600109435',
            'managementFeeRate' => 0.00205,
            'fundManagerUnits' => 5747351,
            'fundManagerUnitsDate' => '2026-09-30',
        ]]));
        $this->renderFundPage('page_fund-stocks.php');
        FakeWordPress::reset();
        FakeWordPress::respondWith(503, '');

        $html = $this->renderFundPage('page_fund-stocks.php');

        $this->assertMatchesRegularExpression('/Management fee<\/span>\s*<span>0,205%<\/span>/', $html);
        $this->assertStringContainsString('5 747 351 units (as of 30.09.2026)', $html);
    }

    #[Test]
    #[PHPUnit\Framework\Attributes\DataProvider('fundTemplates')]
    public function a_fund_page_leaves_out_the_rows_the_fund_list_has_never_given_it(string $template): void
    {
        FakeFundPage::$fields['fund_isin'] = 'EE0000003283';
        FakeWordPress::respondWith(503, '');

        $html = $this->renderFundPage($template);

        $this->assertStringNotContainsString('Management fee', $html);
        $this->assertStringNotContainsString("Fund manager's participation rate in fund", $html);
    }

    /**
     * The switch was approved when the upcoming document was set, so on its date the page
     * makes it with nobody acting.
     */
    #[Test]
    public function an_upcoming_document_is_the_current_one_from_its_date(): void
    {
        $current = 'https://tuleva.ee/wp-content/uploads/2026/03/Prospekt-kehtib-alates-02.03.2026.pdf';
        $upcoming = 'https://tuleva.ee/wp-content/uploads/2026/08/Prospekt-kehtib-alates-01.01.2027.pdf';

        $this->assertSame([$current, $upcoming], tuleva_document_versions($current, $upcoming, '2026-12-31'));
        $this->assertSame([$upcoming, ''], tuleva_document_versions($current, $upcoming, '2027-01-01'));
        $this->assertSame([$upcoming, ''], tuleva_document_versions('', $upcoming, '2027-01-01'));
    }

    /**
     * A template fallback outlives its field: once the current field has moved on to a
     * newer document, the fallback's date has passed as well, and promoting it would put
     * the older document back on the page.
     */
    #[Test]
    public function an_upcoming_document_older_than_the_current_one_is_dropped(): void
    {
        $current = 'https://tuleva.ee/wp-content/uploads/2027/06/Prospekt-kehtib-alates-01.07.2027.pdf';
        $leftover = 'https://tuleva.ee/wp-content/uploads/2026/08/Prospekt-kehtib-alates-01.01.2027.pdf';

        $this->assertSame([$current, ''], tuleva_document_versions($current, $leftover, '2027-08-01'));
    }

    #[Test]
    public function an_upcoming_document_with_no_date_stays_upcoming(): void
    {
        $current = 'https://tuleva.ee/wp-content/uploads/2026/03/Prospekt-kehtib-alates-02.03.2026.pdf';
        $undated = 'https://tuleva.ee/wp-content/uploads/2026/12/Prospekt-uus.pdf';

        $this->assertSame([$current, $undated], tuleva_document_versions($current, $undated, '2030-01-01'));
    }

    /**
     * @return array<string, array{string, string, string}>
     */
    public static function pensionFundsSwitchingOn01012027(): array
    {
        return [
            'TUK75' => ['page_fund-stocks.php', 'TUK75-tingimused-kehtivad-alates-31.08.2026.pdf', 'TUK75-tingimused-kehtivad-alates-01.01.2027-1.pdf'],
            'TUK00' => ['page_fund-bonds.php', 'TUK00-tingimused-kehtivad-alates-31.08.2026.pdf', 'TUK00-tingimused-kehtivad-alates-01.01.2027.pdf'],
        ];
    }

    /**
     * TUK75's and TUK00's new prospectus and terms take effect on 01.01.2027, a public
     * holiday. They switch from the upcoming fields, which an Administrator fills before this
     * deploys, while the current documents still render from the template.
     */
    #[Test]
    #[PHPUnit\Framework\Attributes\DataProvider('pensionFundsSwitchingOn01012027')]
    public function a_pension_page_shows_its_upcoming_documents_as_current_from_their_date(
        string $template,
        string $terms,
        string $upcoming_terms
    ): void {
        FakeFundPage::$fields = [
            'prospectus_upcoming_file' => 'https://tuleva.ee/wp-content/uploads/2026/08/TUK75-ja-TUK00-Prospekt-kehtib-alates-01.01.2027.pdf',
            'terms_upcoming_file' => "https://tuleva.ee/wp-content/uploads/2026/08/$upcoming_terms",
        ];
        FakeFundPage::$today = '2026-12-31';
        $before = $this->renderFundPage($template);

        FakeFundPage::$today = '2027-01-01';
        $after = $this->renderFundPage($template);

        $this->assertStringContainsString($terms, $before);
        $this->assertStringContainsString('effective from 01.01.2027', $before);
        $this->assertStringContainsString('TUK75-ja-TUK00-Prospekt-kehtib-alates-01.01.2027.pdf', $after);
        $this->assertStringContainsString($upcoming_terms, $after);
        $this->assertStringNotContainsString('TUK75-ja-TUK00-Prospekt-kehtib-alates-02.03.2026.pdf', $after);
        $this->assertStringNotContainsString($terms, $after);
        $this->assertStringNotContainsString('effective from', $after);
    }

    /**
     * A document is scheduled only by its upcoming field; the template has no upcoming URL to
     * fall back to, so with the field empty the page keeps the document in force.
     */
    #[Test]
    #[PHPUnit\Framework\Attributes\DataProvider('pensionFundsSwitchingOn01012027')]
    public function a_pension_page_schedules_no_document_its_fields_do_not_hold(
        string $template,
        string $terms,
        string $upcoming_terms
    ): void {
        $before = $this->renderFundPage($template);
        FakeFundPage::$today = '2027-01-02';
        $after = $this->renderFundPage($template);

        foreach ([$before, $after] as $html) {
            $this->assertStringContainsString($terms, $html);
            $this->assertStringContainsString('TUK75-ja-TUK00-Prospekt-kehtib-alates-02.03.2026.pdf', $html);
            $this->assertStringNotContainsString($upcoming_terms, $html);
            $this->assertStringNotContainsString('01.01.2027', $html);
        }
    }

    #[Test]
    public function the_savings_page_switches_its_own_nav_procedure_too(): void
    {
        FakeFundPage::$today = '2027-03-01';
        FakeFundPage::$fields['nav_procedure_upcoming_file']
            = 'https://tuleva.ee/wp-content/uploads/2027/02/TKF100-NAV-sisekord-kehtib-alates-01.03.2027.pdf';

        $html = $this->renderSavingsPage();

        $this->assertStringContainsString('TKF100-NAV-sisekord-kehtib-alates-01.03.2027.pdf', $html);
        $this->assertStringNotContainsString('sisekord-kehtib-alates-18.09.2026.pdf', $html);
        $this->assertStringNotContainsString('effective from', $html);
    }

    /**
     * The REST `acf` values that the app and agent-gateway read have to follow the page,
     * and the next upcoming document needs an empty field to go into. The English page is
     * a translation that reads the Estonian one, so it is never written.
     */
    #[Test]
    public function the_hourly_job_moves_a_due_upcoming_document_into_the_current_field(): void
    {
        FakeFundPage::$pages[self::SAVINGS_TEMPLATE] = [FakeFundPage::ESTONIAN_PAGE, FakeFundPage::ENGLISH_PAGE];
        FakeFundPage::$stored[FakeFundPage::ESTONIAN_PAGE] = ['prospectus_file' => 101, 'prospectus_upcoming_file' => 102];
        FakeAttachments::$urls = [
            101 => 'https://tuleva.ee/wp-content/uploads/2026/09/TKF100-Prospekt-alates-29.10.2026.pdf',
            102 => 'https://tuleva.ee/wp-content/uploads/2026/12/TKF100-Prospekt-kehtib-alates-01.01.2027.pdf',
        ];

        $promoted = tuleva_promote_due_documents('2027-01-01');

        $this->assertSame([['post' => FakeFundPage::ESTONIAN_PAGE, 'field' => 'prospectus_file']], $promoted);
        $this->assertSame(
            [
                ['field_fund_savings_prospectus', 102, FakeFundPage::ESTONIAN_PAGE],
                ['field_fund_savings_prospectus_upcoming', '', FakeFundPage::ESTONIAN_PAGE],
            ],
            FakeFundPage::$updates
        );
    }

    #[Test]
    public function the_hourly_job_leaves_a_document_not_yet_due_and_empties_a_leftover(): void
    {
        FakeFundPage::$pages['page_fund-stocks.php'] = [17533];
        FakeFundPage::$stored[17533] = [
            'prospectus_file' => 201,
            'prospectus_upcoming_file' => 202,
            'terms_file' => 203,
            'terms_upcoming_file' => 204,
        ];
        FakeAttachments::$urls = [
            201 => 'https://tuleva.ee/wp-content/uploads/2026/03/TUK75-ja-TUK00-Prospekt-kehtib-alates-02.03.2026.pdf',
            202 => 'https://tuleva.ee/wp-content/uploads/2026/08/TUK75-ja-TUK00-Prospekt-kehtib-alates-01.01.2027.pdf',
            203 => 'https://tuleva.ee/wp-content/uploads/2027/06/TUK75-tingimused-kehtivad-alates-01.07.2027.pdf',
            204 => 'https://tuleva.ee/wp-content/uploads/2026/08/TUK75-tingimused-kehtivad-alates-01.01.2027-1.pdf',
        ];

        $this->assertSame([], tuleva_promote_due_documents('2026-12-31'));
        $this->assertSame([], FakeFundPage::$updates);

        $this->assertSame([['post' => 17533, 'field' => 'prospectus_file']], tuleva_promote_due_documents('2027-07-15'));
        $this->assertSame([
            ['field_fund_disclosure_stocks_prospectus_file', 202, 17533],
            ['field_fund_disclosure_stocks_prospectus_upcoming_file', '', 17533],
            ['field_fund_disclosure_stocks_terms_upcoming_file', '', 17533],
        ], FakeFundPage::$updates);
    }

    /**
     * A filled field beats a template URL whatever the dates, so the job need not see the template
     * URL a page with an empty current field shows: a due upcoming field is the current document
     * there too. These terms are older than TUK75's template URL (31.08.2026) and still move.
     */
    #[Test]
    public function the_hourly_job_moves_a_due_upcoming_field_into_an_empty_current_field(): void
    {
        FakeFundPage::$pages['page_fund-stocks.php'] = [17533];
        FakeFundPage::$stored[17533] = ['terms_upcoming_file' => 204];
        FakeAttachments::$urls = [
            204 => 'https://tuleva.ee/wp-content/uploads/2026/06/TUK75-tingimused-kehtivad-alates-01.07.2026.pdf',
        ];

        $this->assertSame([], tuleva_promote_due_documents('2026-06-30'));
        $this->assertSame([], FakeFundPage::$updates);

        $this->assertSame([['post' => 17533, 'field' => 'terms_file']], tuleva_promote_due_documents('2026-11-02'));
        $this->assertSame([
            ['field_fund_disclosure_stocks_terms_file', 204, 17533],
            ['field_fund_disclosure_stocks_terms_upcoming_file', '', 17533],
        ], FakeFundPage::$updates);
    }

    /**
     * What the job writes is what the page already showed, so the page does not change when it runs.
     */
    #[Test]
    public function a_page_shows_the_same_before_and_after_the_job_fills_its_empty_current_field(): void
    {
        $upcoming = 'https://tuleva.ee/wp-content/uploads/2026/06/TUK75-tingimused-kehtivad-alates-01.07.2026.pdf';
        FakeFundPage::$pages['page_fund-stocks.php'] = [17533];
        FakeFundPage::$stored[17533] = ['terms_upcoming_file' => 204];
        FakeAttachments::$urls = [204 => $upcoming];
        FakeFundPage::$fields['terms_upcoming_file'] = $upcoming;

        $before = $this->renderFundPage('page_fund-stocks.php');
        tuleva_promote_due_documents(FakeFundPage::TODAY);
        FakeFundPage::$fields = array_map(fn($id) => FakeAttachments::$urls[$id] ?? '', FakeFundPage::$stored[17533]);
        $after = $this->renderFundPage('page_fund-stocks.php');

        $this->assertSame(['terms_upcoming_file' => '', 'terms_file' => $upcoming], FakeFundPage::$fields);
        $this->assertStringContainsString($upcoming, $before);
        $this->assertSame($before, $after);
    }

    /**
     * Every template's current URL gives way to a due upcoming field, even one dated before it.
     */
    #[Test]
    #[PHPUnit\Framework\Attributes\DataProvider('fundTemplates')]
    public function a_due_upcoming_field_replaces_the_template_s_current_url_whatever_its_date(string $template): void
    {
        FakeFundPage::$fields['terms_upcoming_file']
            = 'https://tuleva.ee/wp-content/uploads/2026/06/tingimused-kehtivad-alates-01.07.2026.pdf';

        $html = $this->renderFundPage($template);

        $this->assertStringContainsString('2026/06/tingimused-kehtivad-alates-01.07.2026.pdf', $html);
        $this->assertStringNotContainsString('tingimused-kehtivad-alates-31.08.2026.pdf', $html);
        $this->assertStringNotContainsString('ingimused-kehtivad-alates-18.09.2026.pdf', $html);
        $this->assertStringNotContainsString('effective from 01.07.2026', $html);
    }

    #[Test]
    public function the_savings_page_s_upcoming_nav_procedure_field_replaces_its_template_url_whatever_its_date(): void
    {
        FakeFundPage::$fields['nav_procedure_upcoming_file']
            = 'https://tuleva.ee/wp-content/uploads/2026/08/TKF100-NAV-sisekord-kehtib-alates-01.09.2026.pdf';

        $html = $this->renderSavingsPage();

        $this->assertStringContainsString('TKF100-NAV-sisekord-kehtib-alates-01.09.2026.pdf', $html);
        $this->assertStringNotContainsString('sisekord-kehtib-alates-18.09.2026.pdf', $html);
        $this->assertStringNotContainsString('effective from', $html);
    }

    #[Test]
    public function an_upcoming_document_stays_for_the_next_run_when_the_current_write_did_not_land(): void
    {
        FakeFundPage::$pages[self::SAVINGS_TEMPLATE] = [FakeFundPage::ESTONIAN_PAGE];
        FakeFundPage::$stored[FakeFundPage::ESTONIAN_PAGE] = ['prospectus_file' => 101, 'prospectus_upcoming_file' => 102];
        FakeAttachments::$urls = [
            101 => 'https://tuleva.ee/wp-content/uploads/2026/09/TKF100-Prospekt-alates-29.10.2026.pdf',
            102 => 'https://tuleva.ee/wp-content/uploads/2026/12/TKF100-Prospekt-kehtib-alates-01.01.2027.pdf',
        ];
        FakeFundPage::$failing_writes = ['field_fund_savings_prospectus'];

        $this->assertSame([], tuleva_promote_due_documents('2027-01-01'));
        $this->assertSame(102, FakeFundPage::$stored[FakeFundPage::ESTONIAN_PAGE]['prospectus_upcoming_file']);

        FakeFundPage::$failing_writes = [];
        $this->assertSame(
            [['post' => FakeFundPage::ESTONIAN_PAGE, 'field' => 'prospectus_file']],
            tuleva_promote_due_documents('2027-01-01')
        );
        $this->assertSame(
            ['prospectus_file' => 102, 'prospectus_upcoming_file' => ''],
            FakeFundPage::$stored[FakeFundPage::ESTONIAN_PAGE]
        );
    }

    #[Test]
    public function an_upcoming_document_saved_while_the_job_promotes_the_last_one_stays(): void
    {
        FakeFundPage::$pages[self::SAVINGS_TEMPLATE] = [FakeFundPage::ESTONIAN_PAGE];
        FakeFundPage::$stored[FakeFundPage::ESTONIAN_PAGE] = ['prospectus_file' => 101, 'prospectus_upcoming_file' => 102];
        FakeAttachments::$urls = [
            101 => 'https://tuleva.ee/wp-content/uploads/2026/09/TKF100-Prospekt-alates-29.10.2026.pdf',
            102 => 'https://tuleva.ee/wp-content/uploads/2026/12/TKF100-Prospekt-kehtib-alates-01.01.2027.pdf',
            103 => 'https://tuleva.ee/wp-content/uploads/2027/01/TKF100-Prospekt-kehtib-alates-01.04.2027.pdf',
        ];
        FakeFundPage::$saved_meanwhile = ['field_fund_savings_prospectus' => ['prospectus_upcoming_file' => 103]];

        $this->assertSame(
            [['post' => FakeFundPage::ESTONIAN_PAGE, 'field' => 'prospectus_file']],
            tuleva_promote_due_documents('2027-01-01')
        );
        $this->assertSame(
            ['prospectus_file' => 102, 'prospectus_upcoming_file' => 103],
            FakeFundPage::$stored[FakeFundPage::ESTONIAN_PAGE]
        );
    }

    /**
     * The job writes the fields of the page a translation reads from, so that original has to be
     * published and on the same template; otherwise one template's field keys would be written
     * to another's page.
     */
    #[Test]
    public function the_hourly_job_writes_only_an_original_that_is_published_on_the_same_template(): void
    {
        FakeFundPage::$pages[self::SAVINGS_TEMPLATE] = [FakeFundPage::ENGLISH_PAGE];
        FakeFundPage::$pages['page_fund-stocks.php'] = [FakeFundPage::ESTONIAN_PAGE];
        FakeFundPage::$stored[FakeFundPage::ESTONIAN_PAGE] = ['prospectus_file' => 101, 'prospectus_upcoming_file' => 102];
        FakeAttachments::$urls = [
            101 => 'https://tuleva.ee/wp-content/uploads/2026/09/Prospekt-alates-29.10.2026.pdf',
            102 => 'https://tuleva.ee/wp-content/uploads/2026/12/Prospekt-kehtib-alates-01.01.2027.pdf',
        ];

        $this->assertSame([], tuleva_fund_pages_on(self::SAVINGS_TEMPLATE));

        FakeFundPage::$pages = [self::SAVINGS_TEMPLATE => [FakeFundPage::ESTONIAN_PAGE, FakeFundPage::ENGLISH_PAGE]];
        FakeFundPage::$statuses[FakeFundPage::ESTONIAN_PAGE] = 'draft';

        $this->assertSame([], tuleva_fund_pages_on(self::SAVINGS_TEMPLATE));
        $this->assertSame([], tuleva_promote_due_documents('2027-01-01'));
    }

    /**
     * The fields are written on the Estonian pages only. An English page published with no
     * Estonian original reads its own fields when rendered, but the job leaves them alone.
     */
    #[Test]
    public function the_hourly_job_leaves_an_english_page_with_no_estonian_original_alone(): void
    {
        FakeFundPage::$pages[self::SAVINGS_TEMPLATE] = [FakeFundPage::UNTRANSLATED_ENGLISH_PAGE];
        FakeFundPage::$stored[FakeFundPage::UNTRANSLATED_ENGLISH_PAGE] = ['prospectus_file' => 101, 'prospectus_upcoming_file' => 102];
        FakeAttachments::$urls = [
            101 => 'https://tuleva.ee/wp-content/uploads/2026/09/Prospekt-alates-29.10.2026.pdf',
            102 => 'https://tuleva.ee/wp-content/uploads/2026/12/Prospekt-kehtib-alates-01.01.2027.pdf',
        ];

        $this->assertSame(
            FakeFundPage::UNTRANSLATED_ENGLISH_PAGE,
            tuleva_disclosure_source_post(FakeFundPage::UNTRANSLATED_ENGLISH_PAGE)
        );
        $this->assertSame([], tuleva_fund_pages_on(self::SAVINGS_TEMPLATE));
        $this->assertSame([], tuleva_promote_due_documents('2027-01-01'));
        $this->assertSame([], FakeFundPage::$updates);
    }

    /**
     * Every template takes the switch for both its prospectus and its terms.
     */
    #[Test]
    #[PHPUnit\Framework\Attributes\DataProvider('fundTemplates')]
    public function every_fund_page_switches_its_upcoming_fields_on_their_date(string $template): void
    {
        FakeFundPage::$today = '2027-02-01';
        FakeFundPage::$fields = [
            'prospectus_file' => 'https://tuleva.ee/wp-content/uploads/2026/01/Old-Prospekt-kehtib-alates-01.01.2026.pdf',
            'terms_file' => 'https://tuleva.ee/wp-content/uploads/2026/01/Old-tingimused-kehtivad-alates-01.01.2026.pdf',
            'prospectus_upcoming_file' => 'https://tuleva.ee/wp-content/uploads/2027/01/New-Prospekt-kehtib-alates-01.02.2027.pdf',
            'terms_upcoming_file' => 'https://tuleva.ee/wp-content/uploads/2027/01/New-tingimused-kehtivad-alates-01.02.2027.pdf',
        ];

        $html = $this->renderFundPage($template);

        $this->assertStringContainsString('New-Prospekt-kehtib-alates-01.02.2027.pdf', $html);
        $this->assertStringContainsString('New-tingimused-kehtivad-alates-01.02.2027.pdf', $html);
        $this->assertStringNotContainsString('Old-Prospekt', $html);
        $this->assertStringNotContainsString('Old-tingimused', $html);
        $this->assertStringNotContainsString('effective from', $html);
    }

    #[Test]
    public function the_shared_documents_page_carries_the_documents_every_fund_links_and_nothing_per_fund(): void
    {
        $this->assertSame(
            ['nav_procedure_file', 'nav_procedure_upcoming_file', 'sustainability_risks_file', 'adverse_impacts_file', 'remuneration_policy_file'],
            array_keys(tuleva_fund_disclosure_scope()[TULEVA_SHARED_DOCUMENTS_TEMPLATE])
        );

        foreach (array_column(self::fundTemplates(), 0) as $template) {
            foreach (['sustainability_risks_file', 'adverse_impacts_file', 'remuneration_policy_file'] as $name) {
                $this->assertNull(tuleva_fund_disclosure_requirement($name, $template), "$template $name");
            }
        }
    }

    /**
     * nav_procedure_file is the pension funds' procedure on the shared page and TKF100's own on
     * its page. Neither may show up on the other's fund page.
     */
    #[Test]
    public function the_shared_nav_procedure_reaches_the_pension_pages_and_not_tkf100(): void
    {
        FakeFundPage::$pages[TULEVA_SHARED_DOCUMENTS_TEMPLATE] = [self::SHARED_PAGE];
        FakeFundPage::$page_fields[self::SHARED_PAGE] = [
            'nav_procedure_file' => 'https://tuleva.ee/wp-content/uploads/2027/02/Pensionifondide-NAV-sisekord-kehtib-alates-01.02.2027.pdf',
        ];
        FakeFundPage::$fields['nav_procedure_file']
            = 'https://tuleva.ee/wp-content/uploads/2027/02/TKF100-NAV-sisekord-kehtib-alates-01.02.2027.pdf';

        foreach (self::PENSION_TEMPLATES as $template) {
            $html = $this->renderFundPage($template);
            $this->assertStringContainsString('Pensionifondide-NAV-sisekord-kehtib-alates-01.02.2027.pdf', $html, $template);
            $this->assertStringNotContainsString('TKF100-NAV-sisekord', $html, $template);
        }

        $savings = $this->renderSavingsPage();
        $this->assertStringContainsString('TKF100-NAV-sisekord-kehtib-alates-01.02.2027.pdf', $savings);
        $this->assertStringNotContainsString('Pensionifondide-NAV-sisekord', $savings);
    }

    /**
     * Before this page the upcoming URL was always empty, so the row never rendered. Now it is a
     * field, and a filename with no date must not claim one.
     */
    #[Test]
    public function an_undated_upcoming_pension_nav_procedure_claims_no_date(): void
    {
        FakeFundPage::$pages[TULEVA_SHARED_DOCUMENTS_TEMPLATE] = [self::SHARED_PAGE];
        FakeFundPage::$page_fields[self::SHARED_PAGE] = [
            'nav_procedure_upcoming_file' => 'https://tuleva.ee/wp-content/uploads/2027/02/Pensionifondide-NAV-sisekord-uus.pdf',
        ];

        $line = $this->lineContaining('Pensionifondide-NAV-sisekord-uus.pdf', explode('<br>', $this->renderFundPage('page_fund-bonds.php')));

        $this->assertStringContainsString('Estonian)', $line);
        $this->assertStringNotContainsString('effective from', $line);
    }

    /**
     * Until an Administrator creates the page and its fields are set, a fund page links what
     * it links today.
     */
    #[Test]
    #[PHPUnit\Framework\Attributes\DataProvider('fundTemplates')]
    public function a_fund_page_links_today_s_shared_documents_while_there_is_no_shared_page(string $template): void
    {
        $html = $this->renderFundPage($template);

        $this->assertStringContainsString('2025/08/Jatkusuutlikkusriskide-arvesse-votmise-pohimotted-13.08.2025.pdf', $html);
        $this->assertStringContainsString('negatiivse-moju-mittearvestamine_16.06.2026-1.pdf', $html);
        $this->assertStringContainsString('Tuleva-Fondid-AS-tasustamise-pohimotted-11.06.2026.pdf', $html);
    }

    #[Test]
    #[PHPUnit\Framework\Attributes\DataProvider('fundTemplates')]
    public function a_fund_page_links_the_shared_page_s_documents_once_they_are_set(string $template): void
    {
        FakeFundPage::$pages[TULEVA_SHARED_DOCUMENTS_TEMPLATE] = [self::SHARED_PAGE];
        FakeFundPage::$page_fields[self::SHARED_PAGE] = [
            'sustainability_risks_file' => 'https://tuleva.ee/wp-content/uploads/2027/01/Jatkusuutlikkusriskid-kehtib-alates-01.02.2027.pdf',
            'remuneration_policy_file' => 'https://tuleva.ee/wp-content/uploads/2027/01/Tasustamise-pohimotted-01.02.2027.pdf',
        ];

        $html = $this->renderFundPage($template);

        $this->assertStringContainsString('Jatkusuutlikkusriskid-kehtib-alates-01.02.2027.pdf', $html);
        $this->assertStringContainsString('Tasustamise-pohimotted-01.02.2027.pdf', $html);
        $this->assertStringNotContainsString('Jatkusuutlikkusriskide-arvesse-votmise-pohimotted-13.08.2025.pdf', $html);
        $this->assertStringContainsString('negatiivse-moju-mittearvestamine_16.06.2026-1.pdf', $html, 'an empty field keeps today\'s URL');
    }

    /**
     * The pension funds' NAV procedure switches on its date like a fund's own upcoming
     * document.
     */
    #[Test]
    public function the_pension_funds_nav_procedure_switches_on_its_date_from_the_shared_page(): void
    {
        FakeFundPage::$pages[TULEVA_SHARED_DOCUMENTS_TEMPLATE] = [self::SHARED_PAGE];
        FakeFundPage::$page_fields[self::SHARED_PAGE] = [
            'nav_procedure_upcoming_file' => 'https://tuleva.ee/wp-content/uploads/2027/02/Pensionifondide-NAV-sisekord-kehtib-alates-01.03.2027.pdf',
        ];

        FakeFundPage::$today = '2027-02-28';
        $before = $this->renderFundPage('page_fund-stocks.php');
        FakeFundPage::$today = '2027-03-01';
        $after = $this->renderFundPage('page_fund-stocks.php');

        $this->assertStringContainsString('sisekord-kehtib-alates-18.09.2026.pdf', $before);
        $this->assertStringContainsString('effective from 01.03.2027', $before);
        $this->assertStringContainsString('Pensionifondide-NAV-sisekord-kehtib-alates-01.03.2027.pdf', $after);
        $this->assertStringNotContainsString('sisekord-kehtib-alates-18.09.2026.pdf', $after);
        $this->assertStringNotContainsString('effective from', $after);
    }

    #[Test]
    public function the_hourly_job_moves_the_shared_page_s_due_nav_procedure_too(): void
    {
        FakeFundPage::$pages[TULEVA_SHARED_DOCUMENTS_TEMPLATE] = [self::SHARED_PAGE];
        FakeFundPage::$stored[self::SHARED_PAGE] = ['nav_procedure_file' => 601, 'nav_procedure_upcoming_file' => 602];
        FakeAttachments::$urls = [
            601 => 'https://tuleva.ee/wp-content/uploads/2026/08/Pensionifondide-vara-puhasvaartuse-maaramise-sisekord-kehtib-alates-18.09.2026.pdf',
            602 => 'https://tuleva.ee/wp-content/uploads/2027/02/Pensionifondide-NAV-sisekord-kehtib-alates-01.03.2027.pdf',
        ];

        $this->assertSame([['post' => self::SHARED_PAGE, 'field' => 'nav_procedure_file']], tuleva_promote_due_documents('2027-03-01'));
        $this->assertSame([
            ['field_fund_disclosure_shared_documents_nav_procedure_file', 602, self::SHARED_PAGE],
            ['field_fund_disclosure_shared_documents_nav_procedure_upcoming_file', '', self::SHARED_PAGE],
        ], FakeFundPage::$updates);
    }

    /**
     * A filled field beats the URL in the theme whatever the dates, on the shared page as on a fund
     * page: with its current field empty, an upcoming NAV procedure dated before today's URL still
     * takes over on its date, and the job moves it into the current field.
     */
    #[Test]
    public function an_upcoming_nav_procedure_field_replaces_today_s_url_whatever_its_date_and_is_promoted(): void
    {
        FakeFundPage::$pages[TULEVA_SHARED_DOCUMENTS_TEMPLATE] = [self::SHARED_PAGE];
        $upcoming = 'https://tuleva.ee/wp-content/uploads/2026/08/Pensionifondide-NAV-sisekord-kehtib-alates-01.09.2026.pdf';
        FakeFundPage::$page_fields[self::SHARED_PAGE] = ['nav_procedure_upcoming_file' => $upcoming];
        FakeFundPage::$stored[self::SHARED_PAGE] = ['nav_procedure_upcoming_file' => 602];
        FakeAttachments::$urls = [602 => $upcoming];

        $html = $this->renderFundPage('page_fund-stocks.php');

        $this->assertStringContainsString('kehtib-alates-01.09.2026.pdf', $html);
        $this->assertStringNotContainsString('sisekord-kehtib-alates-18.09.2026.pdf', $html);
        $this->assertStringNotContainsString('effective from 01.09.2026', $html);
        $this->assertSame([['post' => self::SHARED_PAGE, 'field' => 'nav_procedure_file']], tuleva_promote_due_documents('2026-11-02'));
        $this->assertSame(
            ['nav_procedure_upcoming_file' => '', 'nav_procedure_file' => 602],
            FakeFundPage::$stored[self::SHARED_PAGE]
        );
    }

    /**
     * The procedure in force used to live only in the upcoming field once its date passed, so the
     * next upcoming upload replaced it and the pension pages went back to the superseded URL in the
     * theme. The job now moves it into the current field first.
     */
    #[Test]
    public function the_next_upcoming_nav_procedure_does_not_take_the_one_in_force_off_the_pension_pages(): void
    {
        FakeFundPage::$pages[TULEVA_SHARED_DOCUMENTS_TEMPLATE] = [self::SHARED_PAGE];
        FakeAttachments::$urls = [
            602 => 'https://tuleva.ee/wp-content/uploads/2027/02/Pensionifondide-NAV-sisekord-kehtib-alates-01.03.2027.pdf',
            603 => 'https://tuleva.ee/wp-content/uploads/2027/08/Pensionifondide-NAV-sisekord-kehtib-alates-01.09.2027.pdf',
        ];
        FakeFundPage::$stored[self::SHARED_PAGE] = ['nav_procedure_upcoming_file' => 602];
        $this->showStoredFields(self::SHARED_PAGE);

        FakeFundPage::$today = '2027-03-01';
        $this->assertStringContainsString('NAV-sisekord-kehtib-alates-01.03.2027.pdf', $this->renderFundPage('page_fund-stocks.php'));
        tuleva_promote_due_documents(FakeFundPage::$today);
        $this->assertSame(
            ['nav_procedure_upcoming_file' => '', 'nav_procedure_file' => 602],
            FakeFundPage::$stored[self::SHARED_PAGE]
        );

        FakeFundPage::$stored[self::SHARED_PAGE]['nav_procedure_upcoming_file'] = 603;
        $this->showStoredFields(self::SHARED_PAGE);
        FakeFundPage::$today = '2027-03-02';
        $html = $this->renderFundPage('page_fund-stocks.php');

        $this->assertMatchesRegularExpression(
            '/NAV-sisekord-kehtib-alates-01\.03\.2027\.pdf.*?<br>.*?NAV-sisekord-kehtib-alates-01\.09\.2027\.pdf.*?effective from 01\.09\.2027/s',
            $html
        );
        $this->assertStringNotContainsString('sisekord-kehtib-alates-18.09.2026.pdf', $html);
    }

    #[Test]
    public function the_private_shared_page_is_read(): void
    {
        FakeFundPage::$pages[TULEVA_SHARED_DOCUMENTS_TEMPLATE] = [self::SHARED_PAGE];
        FakeFundPage::$statuses[self::SHARED_PAGE] = 'private';
        FakeFundPage::$page_fields[self::SHARED_PAGE] = ['remuneration_policy_file' => 'https://tuleva.ee/policy.pdf'];

        $this->assertSame('https://tuleva.ee/policy.pdf', tuleva_shared_document_url('remuneration_policy_file', 'https://tuleva.ee/today.pdf'));
    }

    #[Test]
    public function a_shared_page_in_draft_is_not_read(): void
    {
        FakeFundPage::$pages[TULEVA_SHARED_DOCUMENTS_TEMPLATE] = [self::SHARED_PAGE];
        FakeFundPage::$statuses[self::SHARED_PAGE] = 'draft';
        FakeFundPage::$page_fields[self::SHARED_PAGE] = ['remuneration_policy_file' => 'https://tuleva.ee/draft.pdf'];

        $this->assertSame('https://tuleva.ee/today.pdf', tuleva_shared_document_url('remuneration_policy_file', 'https://tuleva.ee/today.pdf'));
    }

    #[Test]
    public function with_two_pages_on_the_shared_template_the_oldest_is_read(): void
    {
        FakeFundPage::$pages[TULEVA_SHARED_DOCUMENTS_TEMPLATE] = [self::SHARED_PAGE + 5, self::SHARED_PAGE];
        FakeFundPage::$page_fields[self::SHARED_PAGE] = ['remuneration_policy_file' => 'https://tuleva.ee/oldest.pdf'];
        FakeFundPage::$page_fields[self::SHARED_PAGE + 5] = ['remuneration_policy_file' => 'https://tuleva.ee/newer.pdf'];

        $this->assertSame('https://tuleva.ee/oldest.pdf', tuleva_shared_document_url('remuneration_policy_file'));
    }

    /**
     * The fund pages read one page, so a second one on the template changes nothing they show,
     * and whoever edits it has to be told.
     */
    #[Test]
    public function a_second_page_on_the_shared_template_is_reported(): void
    {
        FakeFundPage::$pages[TULEVA_SHARED_DOCUMENTS_TEMPLATE] = [self::SHARED_PAGE + 5, self::SHARED_PAGE];
        FakeFundPage::$statuses[self::SHARED_PAGE] = 'private';

        $problem = tuleva_shared_documents_page_problem();

        $this->assertStringContainsString('2 pages are on the Fund shared documents template', $problem);
        $this->assertStringContainsString('read only page ' . self::SHARED_PAGE, $problem);
    }

    /**
     * The page is never shown, and a draft, what a page created over REST is unless it says
     * otherwise, would not be read; so a page on the shared template is saved as private.
     */
    #[Test]
    public function a_page_on_the_shared_template_is_saved_as_private(): void
    {
        FakeFundPage::$pages = [TULEVA_SHARED_DOCUMENTS_TEMPLATE => [self::SHARED_PAGE], 'page_fund-stocks.php' => [17533]];
        $saved = fn(array $data, array $postarr) => tuleva_shared_documents_page_status_on_save($data, $postarr)['post_status'];

        foreach (['draft', 'pending', 'future', 'publish', 'private'] as $status) {
            $this->assertSame('private', $saved(['post_type' => 'page', 'post_status' => $status], ['ID' => self::SHARED_PAGE]), $status);
        }

        $this->assertSame('private', $saved(['post_type' => 'page', 'post_status' => 'draft'], ['ID' => 0, 'page_template' => TULEVA_SHARED_DOCUMENTS_TEMPLATE]));
        $this->assertSame('trash', $saved(['post_type' => 'page', 'post_status' => 'trash'], ['ID' => self::SHARED_PAGE]));
        $this->assertSame('draft', $saved(['post_type' => 'page', 'post_status' => 'draft'], ['ID' => 17533]));
        $this->assertSame('publish', $saved(['post_type' => 'post', 'post_status' => 'publish'], ['ID' => 0, 'page_template' => TULEVA_SHARED_DOCUMENTS_TEMPLATE]));
    }

    /**
     * Over REST WordPress sets the template only after the page is saved, so the status is set
     * from the request.
     */
    #[Test]
    public function a_page_created_over_rest_on_the_shared_template_is_saved_as_private(): void
    {
        FakeFundPage::$pages = [TULEVA_SHARED_DOCUMENTS_TEMPLATE => [self::SHARED_PAGE]];
        $saved = fn(object $post, array $request) => tuleva_shared_documents_page_status_over_rest($post, $request);

        $this->assertSame('private', $saved((object) [], ['template' => TULEVA_SHARED_DOCUMENTS_TEMPLATE])->post_status);
        $this->assertSame('private', $saved((object) ['post_status' => 'publish'], ['template' => TULEVA_SHARED_DOCUMENTS_TEMPLATE])->post_status);
        $this->assertSame('private', $saved((object) ['ID' => self::SHARED_PAGE, 'post_status' => 'draft'], [])->post_status);
        $this->assertSame('draft', $saved((object) ['post_status' => 'draft'], ['template' => 'page_fund-stocks.php'])->post_status);
        $this->assertObjectNotHasProperty('post_status', $saved((object) [], []));
    }

    /**
     * A REST update runs rest_pre_insert_page, then wp_insert_post_data with the page as it is
     * stored merged in, its template still the one the request is changing.
     */
    private function saveOverRest(int $page, string $status, array $request): string
    {
        $post = tuleva_shared_documents_page_status_over_rest((object) ['ID' => $page, 'post_status' => $status], $request);

        return tuleva_shared_documents_page_status_on_save(
            ['post_type' => 'page', 'post_status' => $post->post_status],
            ['ID' => $page, 'page_template' => (string) get_page_template_slug($page)]
        )['post_status'];
    }

    #[Test]
    public function moving_the_shared_page_off_its_template_over_rest_keeps_the_status_asked_for(): void
    {
        FakeFundPage::$pages = [TULEVA_SHARED_DOCUMENTS_TEMPLATE => [self::SHARED_PAGE]];

        $this->assertSame('publish', $this->saveOverRest(self::SHARED_PAGE, 'publish', ['template' => '']));
    }

    /**
     * The block editor saves over REST and sends the template only when it changes.
     */
    #[Test]
    public function a_block_editor_save_takes_the_template_it_sets_and_otherwise_the_page_s(): void
    {
        FakeFundPage::$pages = [TULEVA_SHARED_DOCUMENTS_TEMPLATE => [self::SHARED_PAGE]];

        $this->assertSame('publish', $this->saveOverRest(self::SHARED_PAGE, 'publish', ['template' => 'page_fund-stocks.php']));
        $this->resetRestTemplates();
        $this->assertSame('private', $this->saveOverRest(self::SHARED_PAGE, 'publish', ['title' => 'Fund shared documents']));
    }

    #[Test]
    public function one_shared_page_that_is_read_or_none_yet_is_not_a_problem(): void
    {
        $this->assertSame('', tuleva_shared_documents_page_problem());

        FakeFundPage::$pages[TULEVA_SHARED_DOCUMENTS_TEMPLATE] = [self::SHARED_PAGE, self::SHARED_PAGE + 5];
        FakeFundPage::$statuses[self::SHARED_PAGE] = 'private';
        FakeFundPage::$statuses[self::SHARED_PAGE + 5] = 'trash';
        $this->resetSharedPageLookup();

        $this->assertSame('', tuleva_shared_documents_page_problem());
    }

    /**
     * A translation reads its Estonian original, so it is not a second page.
     */
    /**
     * The pages counted are the ones the fund pages could read: a translation on the template
     * whose Estonian original is on another one is neither.
     */
    #[Test]
    public function a_translation_whose_original_is_on_another_template_is_not_a_second_page(): void
    {
        FakeFundPage::$pages = [
            TULEVA_SHARED_DOCUMENTS_TEMPLATE => [self::SHARED_PAGE, FakeFundPage::ENGLISH_PAGE],
            self::SAVINGS_TEMPLATE => [FakeFundPage::ESTONIAN_PAGE],
        ];

        $this->assertSame('', tuleva_shared_documents_page_problem());
    }

    #[Test]
    public function a_translation_of_the_shared_page_is_not_a_second_one(): void
    {
        FakeFundPage::$pages[TULEVA_SHARED_DOCUMENTS_TEMPLATE] = [FakeFundPage::ESTONIAN_PAGE, FakeFundPage::ENGLISH_PAGE];
        FakeFundPage::$statuses[FakeFundPage::ESTONIAN_PAGE] = 'private';

        $this->assertSame('', tuleva_shared_documents_page_problem());
    }

    /**
     * Said where it matters: on the edit screen of each page that reads the shared page, and
     * of the shared page itself.
     */
    #[Test]
    public function the_problem_shows_on_the_edit_screens_of_the_fund_pages_and_the_shared_page(): void
    {
        FakeFundPage::$pages = [
            'page_fund-stocks.php' => [17533],
            'page_front.php' => [2],
            TULEVA_SHARED_DOCUMENTS_TEMPLATE => [self::SHARED_PAGE, self::SHARED_PAGE + 5],
        ];
        FakeFundPage::$screen = (object) ['base' => 'post', 'post_type' => 'page'];

        foreach ([17533, self::SHARED_PAGE] as $page) {
            FakeFundPage::$post_id = $page;
            $this->assertStringContainsString('Keep one page on the template', tuleva_shared_documents_page_notice(), "page $page");
        }

        FakeFundPage::$post_id = 2;
        $this->assertSame('', tuleva_shared_documents_page_notice());

        FakeFundPage::$post_id = 17533;
        FakeFundPage::$screen = (object) ['base' => 'edit', 'post_type' => 'page'];
        $this->assertSame('', tuleva_shared_documents_page_notice());

        FakeFundPage::$screen = null;
        $this->assertSame('', tuleva_shared_documents_page_notice());
    }

    /**
     * The block editor hides admin notices and takes its own, so each editor shows the warning
     * once and runs the lookup once. It can be dismissed: the block editor does not reload on save.
     */
    #[Test]
    public function the_warning_shows_once_on_either_editor_and_can_be_dismissed(): void
    {
        FakeFundPage::$pages = [
            'page_fund-stocks.php' => [17533],
            TULEVA_SHARED_DOCUMENTS_TEMPLATE => [self::SHARED_PAGE, self::SHARED_PAGE + 5],
        ];
        FakeFundPage::$post_id = 17533;
        FakeFundPage::$screen = new class {
            public string $base = 'post';
            public string $post_type = 'page';
            public bool $block_editor = true;

            public function is_block_editor(): bool
            {
                return $this->block_editor;
            }
        };

        ob_start();
        tuleva_shared_documents_page_admin_notice();
        $admin_notice = (string) ob_get_clean();
        tuleva_shared_documents_page_block_editor_notice();

        $this->assertSame('', $admin_notice);
        $this->assertCount(1, FakeFundPage::$inline_scripts);
        $this->assertSame('wp-notices', FakeFundPage::$inline_scripts[0][0]);
        $this->assertStringContainsString('isDismissible: true', FakeFundPage::$inline_scripts[0][1]);

        FakeFundPage::$screen->block_editor = false;
        ob_start();
        tuleva_shared_documents_page_admin_notice();
        $admin_notice = (string) ob_get_clean();

        $this->assertStringContainsString('is-dismissible', $admin_notice);
        $this->assertStringContainsString('Keep one page on the template', $admin_notice);
    }

    /**
     * The documents show on the fund pages only. The page that holds them answers 404 to
     * everyone, so publishing it by mistake does not put a second list of them on the site.
     */
    #[Test]
    public function the_shared_page_is_never_shown(): void
    {
        FakeFundPage::$pages[TULEVA_SHARED_DOCUMENTS_TEMPLATE] = [self::SHARED_PAGE];
        FakeFundPage::$page_fields[self::SHARED_PAGE] = ['remuneration_policy_file' => 'https://tuleva.ee/policy.pdf'];
        $GLOBALS['wp_query'] = new class {
            public bool $is_404 = false;

            public function set_404(): void
            {
                $this->is_404 = true;
            }
        };

        ob_start();
        try {
            include __DIR__ . '/../../page_fund-shared-documents.php';
        } finally {
            $html = (string) ob_get_clean();
            $query = $GLOBALS['wp_query'];
            unset($GLOBALS['wp_query']);
        }

        $this->assertTrue($query->is_404);
        $this->assertSame(404, FakeFundPage::$status_sent);
        $this->assertSame(['404'], FakeFundPage::$template_parts);
        $this->assertStringNotContainsString('policy.pdf', $html);
    }

    /**
     * The shared documents are field values now, not paths in the theme, so they are escaped
     * into the link like every other field URL on the page.
     */
    #[Test]
    #[PHPUnit\Framework\Attributes\DataProvider('fundTemplates')]
    public function the_shared_documents_are_escaped_into_their_links(string $template): void
    {
        FakeFundPage::$pages[TULEVA_SHARED_DOCUMENTS_TEMPLATE] = [self::SHARED_PAGE];
        FakeFundPage::$page_fields[self::SHARED_PAGE] = [
            'nav_procedure_file' => 'https://tuleva.ee/NAV-sisekord-kehtib-alates-01.10.2026.pdf?"data-x="nav',
            'nav_procedure_upcoming_file' => 'https://tuleva.ee/NAV-sisekord-kehtib-alates-01.03.2027.pdf?"data-x="nav-upcoming',
            'sustainability_risks_file' => 'https://tuleva.ee/Jatkusuutlikkus.pdf?"data-x="sustainability',
            'adverse_impacts_file' => 'javascript:alert(document.cookie)',
            'remuneration_policy_file' => 'https://tuleva.ee/Tasustamine.pdf?"data-x="remuneration',
        ];

        $html = $this->renderFundPage($template);

        $this->assertStringContainsString('href="https://tuleva.ee/Tasustamine.pdf?data-x=remuneration"', $html);
        $this->assertStringNotContainsString('"data-x=', $html);
        $this->assertStringNotContainsString('javascript:', $html);
    }

    /**
     * The fields switch by date and the template's URL fills in where they leave no current
     * document: a due upcoming field takes over from it whatever its date, and between two fields
     * an upcoming one dated on or before the current one is a leftover.
     */
    #[Test]
    public function the_fields_go_over_the_template_s_url(): void
    {
        $template = 'https://tuleva.ee/t/Prospekt-kehtib-alates-31.08.2026.pdf';
        $older = 'https://tuleva.ee/f/Prospekt-kehtib-alates-01.07.2026.pdf';
        $next = 'https://tuleva.ee/f/Prospekt-kehtib-alates-01.01.2027.pdf';
        $undated = 'https://tuleva.ee/f/Prospekt-uus.pdf';
        $page = fn(string $current, string $upcoming, string $today)
            => tuleva_document_versions_on_page($current, $upcoming, $template, $today);

        $this->assertSame([$template, ''], $page('', '', '2027-01-01'));
        $this->assertSame([$template, $next], $page('', $next, '2026-12-31'));
        $this->assertSame([$next, ''], $page('', $next, '2027-01-01'));
        $this->assertSame([$older, ''], $page('', $older, '2026-11-02'));

        $this->assertSame([$older, $next], $page($older, $next, '2026-12-31'));
        $this->assertSame([$next, ''], $page($older, $next, '2027-01-01'));
        $this->assertSame([$next, ''], $page($next, $older, '2027-01-01'));
        $this->assertSame([$next, ''], $page($undated, $next, '2027-01-01'));
    }

    /**
     * Only the prospectus, the terms and the NAV procedure have an upcoming version; any other
     * document resolves on its own, and one outside the fund's scope to nothing.
     */
    #[Test]
    public function a_document_with_no_upcoming_version_resolves_on_its_own(): void
    {
        FakeFundPage::$template = 'page_fund-stocks.php';

        $this->assertSame(
            ['https://tuleva.ee/kid.pdf', ''],
            tuleva_fund_document('key_investor_info_file', 'https://tuleva.ee/kid.pdf')
        );
        $this->assertSame(['', ''], tuleva_fund_document('investor_rights_file', 'https://tuleva.ee/rights.pdf'));
        $this->assertSame(['', ''], tuleva_fund_document('nav_procedure_file', 'https://tuleva.ee/nav.pdf'));
    }

    /**
     * TKF100 is the one page whose documents all come from fields, with no code URL left
     * to stand in, so it is the only one where a template can be rendered with a
     * disclosure genuinely absent.
     */
    private function renderSavingsPage(): string
    {
        return $this->renderFundPage(self::SAVINGS_TEMPLATE);
    }

    private function renderFundPage(string $template): string
    {
        FakeFundPage::$template = $template;

        ob_start();

        try {
            include __DIR__ . '/../../templates/components/fund-' . tuleva_fund_template_slug($template) . '-details.php';
        } finally {
            $html = (string) ob_get_clean();
        }

        return $html;
    }

    /**
     * @param list<string> $lines
     */
    private function lineContaining(string $needle, array $lines): string
    {
        $matching = array_values(array_filter($lines, fn($line) => str_contains($line, $needle)));
        $this->assertCount(1, $matching, "Expected one line containing: needle=$needle");

        return $matching[0];
    }
}
