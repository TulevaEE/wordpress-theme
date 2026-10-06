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

    public static array $fields = [];
    public static array $english_fields = [];
    public static int $post_id = self::ESTONIAN_PAGE;
    public static string $template = '';

    public static function reset(): void
    {
        self::$fields = [];
        self::$english_fields = [];
        self::$post_id = self::ESTONIAN_PAGE;
        self::$template = '';
    }
}

function get_field($name, $post_id = false)
{
    $fields = ($post_id ?: FakeFundPage::$post_id) === FakeFundPage::ENGLISH_PAGE
        ? FakeFundPage::$english_fields
        : FakeFundPage::$fields;

    return $fields[$name] ?? null;
}

function get_the_ID()
{
    return FakeFundPage::$post_id;
}

/**
 * WPML's answer on production: the English page is a translation of the Estonian one.
 */
function apply_filters($hook, $value, ...$args)
{
    if ($hook === 'wpml_object_id' && $value === FakeFundPage::ENGLISH_PAGE && ($args[2] ?? null) === 'et') {
        return FakeFundPage::ESTONIAN_PAGE;
    }

    return $value;
}

function get_page_template_slug($post = null)
{
    return FakeFundPage::$template;
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

if (!function_exists('get_the_title')) {
    function get_the_title($post = 0)
    {
        return 'Tuleva Maailma Aktsiate Pensionifond';
    }
}

require_once __DIR__ . '/../support/FakeOptions.php';
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

    protected function setUp(): void
    {
        FakeFundPage::reset();
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
        foreach (array_keys(tuleva_fund_disclosure_scope()) as $template) {
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
