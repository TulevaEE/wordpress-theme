<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * The Yoast SEO variables registered while the test runs, by name.
 */
final class FakeYoastVariables
{
    /** @var array<string, array{callback: callable, type: string, help: string}> */
    public static array $variables = [];
}

if (!function_exists('wpseo_register_var_replacement')) {
    function wpseo_register_var_replacement($var, $replace_function, $type = 'advanced', $help_text = '')
    {
        FakeYoastVariables::$variables[$var] = ['callback' => $replace_function, 'type' => $type, 'help' => $help_text];
    }
}

/**
 * The filter WordPress is running, as doing_filter() answers it.
 */
final class FakeRunningFilter
{
    public static ?string $hook = null;
}

if (!function_exists('doing_filter')) {
    function doing_filter($hook_name = null)
    {
        return $hook_name !== null && $hook_name === FakeRunningFilter::$hook;
    }
}

/**
 * The rest of WordPress the shortcodes call. Guarded because other test files stand the same
 * ones up and PHPUnit runs them all in one process. Whichever current_user_can() loads first,
 * it does not grant edit_posts, so the registered shortcodes render as for a reader.
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

if (!function_exists('current_user_can')) {
    function current_user_can($capability, ...$args)
    {
        return false;
    }
}

require_once __DIR__ . '/../support/FakeFilters.php';
require_once __DIR__ . '/../support/FakeOptions.php';
require_once __DIR__ . '/../support/FakeWordPress.php';
require_once __DIR__ . '/../../helpers/funds-api.php';
require_once __DIR__ . '/../../helpers/fund-figures.php';
require_once __DIR__ . '/../../helpers/fund-figure-shortcodes.php';

/**
 * A fund's ongoing charges and management fee in text typed in WP Admin print as the fund
 * pages print them, and a sentence never gets a gap: no figure or an unknown fund prints
 * the fallback, and only an editor sees why.
 */
final class FundFigureShortcodesTest extends TestCase
{
    private const ONGOING = 'tuleva_ongoing_charges';
    private const MANAGEMENT = 'tuleva_management_fee';

    protected function setUp(): void
    {
        FakeOptions::reset();
        FakeWordPress::reset();
        FakeYoastVariables::$variables = [];
        FakeRunningFilter::$hook = null;
        unset(FakeFilters::$filters['wpml_current_language']);
    }

    protected function tearDown(): void
    {
        FakeRunningFilter::$hook = null;
        unset(FakeFilters::$filters['wpml_current_language']);
    }

    #[Test]
    public function prints_a_funds_ongoing_charges_with_its_key_information_documents_decimals(): void
    {
        $this->listFunds(['TKF100' => [0.0028, 2], 'TUK75' => [0.0028, 1]]);

        $this->assertSame('0,28%', $this->reader(self::ONGOING, ['fund' => 'TKF100', 'fallback' => '0,28%']));
        $this->assertSame('0,3%', $this->reader(self::ONGOING, ['fund' => 'TUK75', 'fallback' => '0,28%']));
    }

    #[Test]
    public function takes_the_fund_code_in_any_case(): void
    {
        $this->listFunds(['TKF100' => [0.0028, 2]]);

        $this->assertSame('0,28%', $this->reader(self::ONGOING, ['fund' => ' tkf100 ', 'fallback' => '0,28%']));
    }

    #[Test]
    public function prints_a_funds_management_fee_as_the_fund_page_does(): void
    {
        $this->listFunds(['TUK75' => [0.0028, 2]], ['TUK75' => 0.0016]);

        $this->assertSame('0,16%', $this->reader(self::MANAGEMENT, ['fund' => 'TUK75', 'fallback' => '0,16%']));
    }

    #[Test]
    public function writes_the_figure_in_the_pages_language(): void
    {
        $this->listFunds(['TKF100' => [0.0028, 2]]);
        FakeFilters::$filters['wpml_current_language'] = fn() => 'en';

        $this->assertSame('0.28%', $this->reader(self::ONGOING, ['fund' => 'TKF100', 'fallback' => '0.28%']));
    }

    #[Test]
    public function prints_the_last_figure_the_fund_list_returned_while_it_cannot_be_read(): void
    {
        FakeOptions::$options[TULEVA_FUND_FIGURES_OPTION_PREFIX . TULEVA_FUND_ISINS['TKF100']] = self::figures(0.0028, 2, 0.0049);
        FakeWordPress::respondWith(503, '');

        $this->assertSame('0,28%', $this->reader(self::ONGOING, ['fund' => 'TKF100', 'fallback' => '0,3%']));
    }

    #[Test]
    public function prints_the_fallback_when_no_figure_was_ever_read(): void
    {
        FakeWordPress::respondWith(503, '');

        $this->assertSame('0,28%', $this->reader(self::ONGOING, ['fund' => 'TKF100', 'fallback' => '0,28%']));
        $this->assertSame('0,28%', $this->editor(self::ONGOING, ['fund' => 'TKF100', 'fallback' => '0,28%']));
    }

    #[Test]
    public function an_unknown_fund_prints_the_fallback_and_warns_only_an_editor(): void
    {
        $this->listFunds(['TKF100' => [0.0028, 2]]);
        $atts = ['fund' => 'TKF', 'fallback' => '0,28%'];

        $this->assertSame('0,28%', $this->reader(self::ONGOING, $atts));
        $this->assertSame(
            '0,28% <mark class="tuleva-shortcode-warning">[tuleva_ongoing_charges: fund &quot;TKF&quot; is not one of TUK75, TUK00, TUV100, TKF100, all. Only logged-in editors see this.]</mark>',
            $this->editor(self::ONGOING, $atts)
        );
        $this->assertStringContainsString(
            'fund &quot;ALL&quot; is not one of TUK75, TUK00, TUV100, TKF100.',
            $this->editor(self::MANAGEMENT, ['fund' => 'all', 'fallback' => '0,16%'])
        );
    }

    #[Test]
    public function without_a_fallback_an_editor_is_warned_and_a_reader_sees_only_what_there_is(): void
    {
        $this->listFunds(['TKF100' => [0.0028, 2]]);

        $this->assertSame('0,28%', $this->reader(self::ONGOING, ['fund' => 'TKF100']));
        $this->assertSame(
            '0,28% <mark class="tuleva-shortcode-warning">[tuleva_ongoing_charges: it has no fallback, so readers see nothing when there is no figure. Only logged-in editors see this.]</mark>',
            $this->editor(self::ONGOING, ['fund' => 'TKF100'])
        );
        $this->assertSame('', $this->reader(self::ONGOING, ['fund' => 'NONE']));
        $this->assertSame('', $this->reader(self::ONGOING, ''));
    }

    #[Test]
    public function escapes_the_fallback(): void
    {
        FakeWordPress::respondWith(503, '');

        $this->assertSame('&lt;b&gt;0,28%&lt;/b&gt;', $this->reader(self::ONGOING, ['fund' => 'TKF100', 'fallback' => '<b>0,28%</b>']));
    }

    #[Test]
    public function all_funds_print_one_value_or_the_lowest_and_highest(): void
    {
        $this->listFunds(['TUK75' => [0.0028, 2], 'TUK00' => [0.0028, 2], 'TUV100' => [0.0028, 2], 'TKF100' => [0.0028, 2]]);
        $this->assertSame('0,28%', $this->reader(self::ONGOING, ['fund' => 'all', 'fallback' => '0,28%']));

        FakeWordPress::reset();
        FakeOptions::reset();
        $this->listFunds(['TUK75' => [0.0028, 2], 'TUK00' => [0.0028, 2], 'TUV100' => [0.0028, 2], 'TKF100' => [0.0028, 1]]);
        $this->assertSame('0,28%–0,3%', $this->reader(self::ONGOING, ['fund' => 'ALL', 'fallback' => '0,28%']));
    }

    #[Test]
    public function the_registered_shortcodes_render_as_for_a_reader_outside_wp_admin(): void
    {
        $this->listFunds(['TKF100' => [0.0028, 2]], ['TKF100' => 0.0049]);

        $this->assertSame('0,28%', tuleva_ongoing_charges_shortcode(['fund' => 'TKF100', 'fallback' => '0,3%']));
        $this->assertSame('0,49%', tuleva_management_fee_shortcode(['fund' => 'TKF100', 'fallback' => '0,5%']));
        $this->assertSame('0,3%', tuleva_ongoing_charges_shortcode(['fund' => 'TKF', 'fallback' => '0,3%']));
    }

    #[Test]
    public function an_automatic_excerpt_keeps_the_figure_shortcodes_and_anywhere_else_they_are_stripped(): void
    {
        $tags = ['gallery', 'tuleva_ongoing_charges', 'caption', 'tuleva_management_fee'];

        FakeRunningFilter::$hook = 'get_the_excerpt';
        $this->assertSame(['gallery', 'caption'], tuleva_keep_fund_figure_shortcodes_in_excerpts($tags));

        FakeRunningFilter::$hook = null;
        $this->assertSame($tags, tuleva_keep_fund_figure_shortcodes_in_excerpts($tags));
    }

    #[Test]
    public function registers_one_yoast_variable_per_fund_for_its_ongoing_charges(): void
    {
        $this->listFunds(['TKF100' => [0.0028, 2]]);

        tuleva_register_fund_figure_yoast_variables();

        $this->assertSame(
            ['%%tuleva_ongoing_charges_tuk75%%', '%%tuleva_ongoing_charges_tuk00%%', '%%tuleva_ongoing_charges_tuv100%%', '%%tuleva_ongoing_charges_tkf100%%'],
            array_keys(FakeYoastVariables::$variables)
        );
        $this->assertSame('0,28%', (FakeYoastVariables::$variables['%%tuleva_ongoing_charges_tkf100%%']['callback'])('tuleva_ongoing_charges_tkf100', null));
        $this->assertSame('advanced', FakeYoastVariables::$variables['%%tuleva_ongoing_charges_tkf100%%']['type']);
    }

    #[Test]
    public function a_yoast_variable_is_empty_before_any_figure_was_read(): void
    {
        FakeWordPress::respondWith(503, '');

        tuleva_register_fund_figure_yoast_variables();

        $this->assertSame('', (FakeYoastVariables::$variables['%%tuleva_ongoing_charges_tkf100%%']['callback'])());
    }

    private function reader(string $tag, $atts): string
    {
        return tuleva_fund_figure_shortcode($tag, $atts, false);
    }

    private function editor(string $tag, $atts): string
    {
        return tuleva_fund_figure_shortcode($tag, $atts, true);
    }

    /**
     * @param array<string, array{float, int}> $charges ongoing charges and their decimals, by fund
     * @param array<string, float> $managementFees by fund; 0.0049 where not given
     */
    private function listFunds(array $charges, array $managementFees = []): void
    {
        $funds = [];
        foreach ($charges as $fund => [$figure, $decimals]) {
            $funds[] = ['isin' => TULEVA_FUND_ISINS[$fund]] + self::figures($figure, $decimals, $managementFees[$fund] ?? 0.0049);
        }
        foreach (array_diff_key($managementFees, $charges) as $fund => $fee) {
            $funds[] = ['isin' => TULEVA_FUND_ISINS[$fund]] + self::figures(0.0028, 2, $fee);
        }
        FakeWordPress::respondWith(200, json_encode($funds));
    }

    private static function figures(float $ongoingCharges, int $decimals, float $managementFee): array
    {
        return [
            'managementFeeRate' => $managementFee,
            'fundManagerUnits' => 5747351,
            'fundManagerUnitsDate' => '2026-10-31',
            'ongoingChargesFigure' => $ongoingCharges,
            'ongoingChargesPercentDecimals' => $decimals,
        ];
    }
}
