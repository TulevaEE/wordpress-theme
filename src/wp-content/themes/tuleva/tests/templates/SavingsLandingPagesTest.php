<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

if (!defined('TEXT_DOMAIN')) {
    define('TEXT_DOMAIN', 'tuleva');
}

if (!defined('ICL_LANGUAGE_CODE')) {
    define('ICL_LANGUAGE_CODE', 'et');
}

/**
 * The rest of WordPress the three savings fund landing pages call. Guarded because
 * FundDisclosuresTest and ReportLinkTest stand the same ones up and PHPUnit runs them all in
 * one process; they behave alike here. No filter answers the page language, as in the other
 * template tests, and the fee figures treat that as Estonian.
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

if (!function_exists('add_shortcode')) {
    function add_shortcode($tag, $callback)
    {
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

require_once __DIR__ . '/../support/FakeTranslations.php';
require_once __DIR__ . '/../support/FakeFilters.php';
require_once __DIR__ . '/../support/FakeOptions.php';
require_once __DIR__ . '/../support/FakeWordPress.php';
require_once __DIR__ . '/../support/FakeEscaping.php';
require_once __DIR__ . '/../../helpers/funds-api.php';
require_once __DIR__ . '/../../helpers/fund-figures.php';
require_once __DIR__ . '/../../helpers/counts.php';
require_once __DIR__ . '/../../helpers/extras.php';

/**
 * The savings fund landing pages print TKF100's ongoing charges, and the landing page the
 * range of all four funds', into Estonian sentences through printf. A stray % in one of
 * them takes the page down, so every render here also fails on any PHP warning or notice.
 */
final class SavingsLandingPagesTest extends TestCase
{
    private const LANDING = 'templates/savings-fund-landing-content.php';
    private const CHILD = 'templates/child-savings-content.php';
    private const COMPANY = 'templates/company-savings-content.php';

    protected function setUp(): void
    {
        FakeOptions::reset();
        FakeWordPress::reset();
        FakeTranslations::load(__DIR__ . '/../../lang/et.mo');
        // The child page's count of savers, as the day's cache holds it.
        FakeWordPress::$transients['tuleva_investor_count'] = 85000;
    }

    protected function tearDown(): void
    {
        FakeTranslations::reset();
    }

    #[Test]
    public function the_pages_print_the_savings_funds_ongoing_charges_from_the_fund_list(): void
    {
        $this->listFunds(['TUK75' => [0.0028, 2], 'TUK00' => [0.0028, 2], 'TUV100' => [0.0028, 2], 'TKF100' => [0.0028, 2]]);

        $landing = $this->render(self::LANDING);
        $child = $this->render(self::CHILD);
        $company = $this->render(self::COMPANY);

        $this->assertStringContainsString('<p>Fondi tasu 0,28% aastas, ilma ostu-, müügi- ja muude lisatasudeta.</p>', $landing);
        $this->assertStringContainsString('<span>Tasu <strong>0,28%</strong> aastas, lisatasudeta</span>', $landing);
        $this->assertStringContainsString(
            'Tuleva kõigi fondide tasu on 0,28% aastas. Teiste fondide tasusid saad võrrelda <a href="https://www.pensionikeskus.ee/',
            $landing
        );
        $this->assertStringContainsString('<span>Tasu <strong>0,28%</strong> aastas, lisatasudeta</span>', $child);
        $this->assertStringContainsString('<p>Fondi jooksvad tasud on 0,28% aastas.', $child);
        $this->assertStringContainsString('laiapõhjaline indeksfond tasuga 0,28% aastas – ja selle eest seisame meie.', $child);
        $this->assertStringContainsString('<p>Fondi tasu 0,28% aastas, ilma ostu-, müügi- ja muude lisatasudeta.</p>', $company);
        $this->assertStringContainsString('<span>Tasu <strong>0,28%</strong> aastas, lisatasudeta</span>', $company);
        $this->assertStringContainsString('indeksfond, mille tasu on 0,28% aastas.', $company);
        $this->assertStringContainsString('Näiteks 10&nbsp;000 euro pealt on fondi tasu 28 eurot aastas', $company);
    }

    /**
     * Should TKF100's key information document print one decimal while the pension funds'
     * print two, the same 0.0028 is 0,3% on the savings fund's pages and 0,28% on the others.
     */
    #[Test]
    public function the_pages_print_the_savings_fund_with_its_own_decimals_and_all_funds_as_a_range(): void
    {
        $this->listFunds(['TUK75' => [0.0028, 2], 'TUK00' => [0.0028, 2], 'TUV100' => [0.0028, 2], 'TKF100' => [0.0028, 1]]);

        $landing = $this->render(self::LANDING);
        $child = $this->render(self::CHILD);
        $company = $this->render(self::COMPANY);

        $this->assertStringContainsString('Tuleva fondide tasud on 0,28%–0,3% aastas. Teiste fondide tasusid saad võrrelda <a href=', $landing);
        $this->assertStringNotContainsString('Tuleva kõigi fondide tasu', $landing);
        $this->assertStringContainsString('<p>Fondi tasu 0,3% aastas, ilma ostu-, müügi- ja muude lisatasudeta.</p>', $landing);
        $this->assertStringContainsString('<span>Tasu <strong>0,3%</strong> aastas, lisatasudeta</span>', $child);
        $this->assertStringContainsString('indeksfond, mille tasu on 0,3% aastas.', $company);
        $this->assertStringContainsString('Näiteks 10&nbsp;000 euro pealt on fondi tasu 30 eurot aastas', $company);
    }

    /**
     * @return array<string, array{string, list<string>}>
     */
    public static function pagesWithTheirFeeSentences(): array
    {
        return [
            'savings fund landing' => [self::LANDING, ['Fondi tasu ', 'Tasu <strong>', 'fondide tasu']],
            'child savings' => [self::CHILD, ['Tasu <strong>', 'jooksvad tasud on', 'indeksfond tasuga']],
            'company savings' => [self::COMPANY, ['Fondi tasu ', 'Tasu <strong>', 'mille tasu on', 'euro pealt on fondi tasu']],
        ];
    }

    /**
     * Before the fund list has ever given TKF100's figure, a sentence stating it is left out
     * rather than printed with a gap, and the rest of the page renders.
     */
    #[Test]
    #[DataProvider('pagesWithTheirFeeSentences')]
    public function a_page_leaves_out_the_fee_before_the_fund_list_has_given_it(string $page, array $feeSentences): void
    {
        FakeWordPress::respondWith(503, '');

        $html = $this->render($page);

        foreach ($feeSentences as $sentence) {
            $this->assertStringNotContainsString($sentence, $html);
        }
        $this->assertStringContainsString('</main>', $html);
    }

    /**
     * @param array<string, array{float, int}> $charges figure and decimals by fund
     */
    private function listFunds(array $charges): void
    {
        $funds = [];
        foreach ($charges as $fund => [$figure, $decimals]) {
            $funds[] = [
                'isin' => TULEVA_FUND_ISINS[$fund],
                'managementFeeRate' => 0.00205,
                'fundManagerUnits' => 5747351,
                'fundManagerUnitsDate' => '2026-10-31',
                'ongoingChargesFigure' => $figure,
                'ongoingChargesPercentDecimals' => $decimals,
            ];
        }
        FakeWordPress::respondWith(200, json_encode($funds));
    }

    /**
     * Renders a page and fails on any PHP warning or notice it raises, as well as on a
     * placeholder printf left unfilled.
     */
    private function render(string $template): string
    {
        $problems = [];
        set_error_handler(function (int $level, string $message, string $file, int $line) use (&$problems): bool {
            $problems[] = "$message at $file:$line";

            return true;
        });
        ob_start();

        try {
            include __DIR__ . '/../../' . $template;
        } finally {
            $html = (string) ob_get_clean();
            restore_error_handler();
        }

        $this->assertSame([], $problems, $template);
        $this->assertDoesNotMatchRegularExpression('/%(\d+\$)?s/', $html, $template);

        return $html;
    }
}
