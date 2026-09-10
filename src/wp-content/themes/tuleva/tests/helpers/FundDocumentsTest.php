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
    public static array $fields = [];
    public static string $template = '';

    public static function reset(): void
    {
        self::$fields = [];
        self::$template = '';
    }
}

function get_field($name, $post_id = false)
{
    return FakeFundPage::$fields[$name] ?? null;
}

function get_page_template_slug($post = null)
{
    return FakeFundPage::$template;
}

require_once __DIR__ . '/../../helpers/acf/fund-documents.php';

final class FundDocumentsTest extends TestCase
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
        $catalogue = tuleva_fund_document_catalogue();

        foreach (tuleva_fund_document_scope() as $template => $documents) {
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
        foreach (tuleva_fund_document_scope() as $template => $documents) {
            foreach ($documents as $name => $requirement) {
                $this->assertContains(
                    $requirement,
                    [TULEVA_DOCUMENT_REQUIRED, TULEVA_DOCUMENT_OPTIONAL],
                    "$template scopes '$name' as '$requirement'"
                );
            }
        }
    }

    #[Test]
    public function generated_field_keys_are_unique_across_fund_pages(): void
    {
        $keys = [];

        foreach (array_keys(tuleva_fund_document_scope()) as $template) {
            foreach (tuleva_fund_document_field_group($template)['fields'] as $field) {
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

        foreach (tuleva_fund_document_field_group(self::SAVINGS_TEMPLATE)['fields'] as $field) {
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
        foreach (array_keys(tuleva_fund_document_scope()) as $template) {
            $this->assertSame(1, tuleva_fund_document_field_group($template)['show_in_rest'], $template);
        }
    }

    #[Test]
    public function the_investment_report_field_is_named_the_same_on_every_fund(): void
    {
        foreach (array_keys(tuleva_fund_document_scope()) as $template) {
            $this->assertNotNull(
                tuleva_fund_document_requirement('investment_report_file', $template),
                "$template must carry investment_report_file — onboarding-service sets it by name on every fund"
            );
        }
    }

    #[Test]
    public function pension_funds_do_not_carry_ucits_documents(): void
    {
        foreach (self::PENSION_TEMPLATES as $template) {
            $this->assertNull(tuleva_fund_document_requirement('investor_rights_file', $template));
            $this->assertNull(tuleva_fund_document_requirement('nav_procedure_file', $template));
        }

        $this->assertSame(
            TULEVA_DOCUMENT_REQUIRED,
            tuleva_fund_document_requirement('investor_rights_file', self::SAVINGS_TEMPLATE)
        );
        $this->assertSame(
            TULEVA_DOCUMENT_REQUIRED,
            tuleva_fund_document_requirement('nav_procedure_file', self::SAVINGS_TEMPLATE)
        );
    }

    #[Test]
    public function a_document_outside_a_fund_scope_never_falls_back_to_another_fund_document(): void
    {
        FakeFundPage::$template = 'page_fund-stocks.php';

        $this->assertSame(
            '',
            tuleva_fund_document_url('investor_rights_file', 'https://tuleva.ee/tkf100-investor-rights.pdf')
        );
    }

    #[Test]
    public function a_scoped_document_falls_back_only_while_its_field_is_empty(): void
    {
        FakeFundPage::$template = 'page_fund-stocks.php';

        $this->assertSame(
            'https://tuleva.ee/old-prospectus.pdf',
            tuleva_fund_document_url('prospectus_file', 'https://tuleva.ee/old-prospectus.pdf')
        );

        FakeFundPage::$fields['prospectus_file'] = 'https://tuleva.ee/new-prospectus.pdf';

        $this->assertSame(
            'https://tuleva.ee/new-prospectus.pdf',
            tuleva_fund_document_url('prospectus_file', 'https://tuleva.ee/old-prospectus.pdf')
        );
    }

    #[Test]
    public function a_field_left_on_the_array_return_format_still_yields_a_url(): void
    {
        FakeFundPage::$template = self::SAVINGS_TEMPLATE;
        FakeFundPage::$fields['terms_upcoming_file'] = ['url' => 'https://tuleva.ee/upcoming-terms.pdf'];

        $this->assertSame(
            'https://tuleva.ee/upcoming-terms.pdf',
            tuleva_fund_document_url('terms_upcoming_file')
        );
    }

    #[Test]
    public function a_page_that_is_not_a_fund_page_renders_no_documents(): void
    {
        FakeFundPage::$template = 'page_front.php';
        FakeFundPage::$fields['prospectus_file'] = 'https://tuleva.ee/prospectus.pdf';

        $this->assertSame('', tuleva_fund_document_url('prospectus_file', 'https://tuleva.ee/fallback.pdf'));
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

        // key_investor_info_file is required and unset; the two upcoming documents and
        // previous_reports_url are optional and unset.
        $this->assertSame(['key_investor_info_file'], tuleva_fund_documents_missing());
    }

    #[Test]
    public function a_fund_with_every_required_document_reports_no_gaps(): void
    {
        FakeFundPage::$template = self::SAVINGS_TEMPLATE;

        foreach (tuleva_fund_document_scope()[self::SAVINGS_TEMPLATE] as $name => $requirement) {
            if ($requirement === TULEVA_DOCUMENT_REQUIRED) {
                FakeFundPage::$fields[$name] = "https://tuleva.ee/$name.pdf";
            }
        }

        $this->assertSame([], tuleva_fund_documents_missing());
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

    #[Test]
    public function template_slugs_namespace_the_generated_keys_readably(): void
    {
        $this->assertSame('stocks', tuleva_fund_template_slug('page_fund-stocks.php'));
        $this->assertSame('savings', tuleva_fund_template_slug('page_fund-savings.php'));
    }
}
