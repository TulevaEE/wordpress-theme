<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

require_once __DIR__ . '/../support/FakeOptions.php';
require_once __DIR__ . '/../support/FakeWordPress.php';
require_once __DIR__ . '/../../helpers/funds-api.php';
require_once __DIR__ . '/../../helpers/fund-figures.php';

final class FundFiguresTest extends TestCase
{
    private const TKF100 = 'EE0000003283';
    private const TUK75 = 'EE3600109435';
    private const LAST_GOOD_TUK75 = 'tuleva_fund_figures_last_good_EE3600109435';

    protected function setUp(): void
    {
        FakeOptions::reset();
        FakeWordPress::reset();
    }

    #[Test]
    public function readsAFundsManagementFeeAndManagerUnitsFromTheFundList(): void
    {
        $funds = [
            self::listed(self::TUK75, 0.00205, 5747351, '2026-09-30'),
            self::listed(self::TKF100, 0.00178, 202901.49, '2026-09-30'),
        ];

        $this->assertSame(
            self::figures(0.00178, 202901.49, '2026-09-30', 0.0028, 2),
            tuleva_fund_figures(self::TKF100, $funds, null)
        );
    }

    #[Test]
    public function readsTheOngoingChargesWithTheDecimalsTheKeyInformationDocumentPrintsThemWith(): void
    {
        $funds = [
            self::listed(self::TUK75, 0.00205, 5747351, '2026-09-30', 0.0031, 2),
            self::listed(self::TKF100, 0.00178, 202901.49, '2026-09-30', 0.003, 1),
        ];

        $figures = tuleva_fund_figures(self::TKF100, $funds, null);

        $this->assertSame(0.003, $figures['ongoingChargesFigure']);
        $this->assertSame(1, $figures['ongoingChargesPercentDecimals']);
    }

    #[Test]
    public function showsTheLastFiguresWhileTheListCannotBeRead(): void
    {
        $lastGood = self::figures(0.00205, 5747351.0, '2026-09-30', 0.0028, 2);

        $this->assertSame($lastGood, tuleva_fund_figures(self::TUK75, null, $lastGood));
    }

    #[Test]
    public function showsTheLastFiguresWhenTheListNoLongerCarriesTheFund(): void
    {
        $lastGood = self::figures(0.00205, 5747351.0, '2026-09-30', 0.0028, 2);

        $this->assertSame($lastGood, tuleva_fund_figures(self::TUK75, [self::listed(self::TKF100, 0.00178, 1.0, '2026-09-30')], $lastGood));
    }

    #[Test]
    public function keepsTheLastUnitsWhenTheListHasNoneForTheFund(): void
    {
        $lastGood = self::figures(0.00205, 5747351.0, '2026-08-31', 0.0028, 2);
        $withoutUnits = [['isin' => self::TUK75, 'managementFeeRate' => 0.00163, 'ongoingChargesFigure' => 0.0028, 'ongoingChargesPercentDecimals' => 2]];

        $this->assertSame(
            self::figures(0.00163, 5747351.0, '2026-08-31', 0.0028, 2),
            tuleva_fund_figures(self::TUK75, $withoutUnits, $lastGood)
        );
    }

    #[Test]
    public function keepsTheLastFeeWhenTheListHasNoneForTheFund(): void
    {
        $lastGood = self::figures(0.00205, 5747351.0, '2026-08-31', 0.0028, 2);
        $withoutFee = [['isin' => self::TUK75, 'fundManagerUnits' => 5800000, 'fundManagerUnitsDate' => '2026-09-30', 'ongoingChargesFigure' => 0.0028, 'ongoingChargesPercentDecimals' => 2]];

        $this->assertSame(
            self::figures(0.00205, 5800000.0, '2026-09-30', 0.0028, 2),
            tuleva_fund_figures(self::TUK75, $withoutFee, $lastGood)
        );
    }

    /**
     * Decimals without a figure say nothing about the figure the page shows, so the last
     * figure keeps its own decimals.
     */
    #[Test]
    public function keepsTheLastOngoingChargesAndTheirDecimalsWhenTheListHasNoFigureForTheFund(): void
    {
        $lastGood = self::figures(0.00205, 5747351.0, '2026-09-30', 0.0028, 2);
        $withoutFigure = [
            ['isin' => self::TUK75, 'managementFeeRate' => 0.00205, 'fundManagerUnits' => 5747351, 'fundManagerUnitsDate' => '2026-09-30', 'ongoingChargesPercentDecimals' => 1],
        ];
        $unreadableFigure = [
            ['isin' => self::TUK75, 'managementFeeRate' => 0.00205, 'fundManagerUnits' => 5747351, 'fundManagerUnitsDate' => '2026-09-30', 'ongoingChargesFigure' => 'n/a', 'ongoingChargesPercentDecimals' => 1],
        ];

        $this->assertSame($lastGood, tuleva_fund_figures(self::TUK75, $withoutFigure, $lastGood));
        $this->assertSame($lastGood, tuleva_fund_figures(self::TUK75, $unreadableFigure, $lastGood));
    }

    /**
     * With no key information document in force the list falls back to another figure and
     * leaves the decimals out: the last document's decimals do not belong to that figure.
     */
    #[Test]
    public function takesAFigureWithoutDecimalsAsItIsRatherThanWithTheLastDecimals(): void
    {
        $lastGood = self::figures(0.00205, 5747351.0, '2026-09-30', 0.0028, 2);
        $funds = [self::listed(self::TUK75, 0.00205, 5747351, '2026-09-30', 0.00295, null)];

        $this->assertSame(
            self::figures(0.00205, 5747351.0, '2026-09-30', 0.00295, null),
            tuleva_fund_figures(self::TUK75, $funds, $lastGood)
        );
    }

    #[Test]
    public function ignoresDecimalsThatAreNotAWholeNumberFromZeroToFour(): void
    {
        foreach (['2', 2.0, 5, -1, null] as $unreadable) {
            $funds = [self::listed(self::TUK75, 0.00205, 5747351, '2026-09-30', 0.0028, $unreadable)];

            $this->assertNull(
                tuleva_fund_figures(self::TUK75, $funds, null)['ongoingChargesPercentDecimals'],
                var_export($unreadable, true)
            );
        }

        $this->assertSame(0, tuleva_fund_figures(self::TUK75, [self::listed(self::TUK75, 0.00205, 5747351, '2026-09-30', 0.01, 0)], null)['ongoingChargesPercentDecimals']);
        $this->assertSame(4, tuleva_fund_figures(self::TUK75, [self::listed(self::TUK75, 0.00205, 5747351, '2026-09-30', 0.0028, 4)], null)['ongoingChargesPercentDecimals']);
    }

    /**
     * Production's last-good options were stored before the list carried the ongoing
     * charges, so they have no key for them.
     */
    #[Test]
    public function readsALastGoodOptionStoredBeforeTheListCarriedTheOngoingCharges(): void
    {
        $storedBefore = ['managementFeeRate' => 0.00205, 'fundManagerUnits' => 5747351.0, 'fundManagerUnitsDate' => '2026-09-30'];

        $this->assertSame(
            self::figures(0.00205, 5747351.0, '2026-09-30', null, null),
            tuleva_fund_figures(self::TUK75, null, $storedBefore)
        );
        $this->assertSame(
            self::figures(0.00205, 5747351.0, '2026-09-30', null, null),
            tuleva_fund_figures(self::TUK75, [['isin' => self::TUK75]], $storedBefore)
        );
        $this->assertSame(
            self::figures(0.00205, 5747351.0, '2026-09-30', 0.0028, 2),
            tuleva_fund_figures(self::TUK75, [['isin' => self::TUK75, 'ongoingChargesFigure' => 0.0028, 'ongoingChargesPercentDecimals' => 2]], $storedBefore)
        );
    }

    /**
     * A failed read falls back to the stored figures, filled out with the keys an option
     * stored before the ongoing charges lacks. Writing that back would overwrite whatever
     * another request has stored meanwhile from a read that worked.
     */
    #[Test]
    public function remembersNothingWhenTheFundListCannotBeRead(): void
    {
        $storedBefore = ['managementFeeRate' => 0.00205, 'fundManagerUnits' => 5747351.0, 'fundManagerUnitsDate' => '2026-09-30'];
        FakeOptions::$options[self::LAST_GOOD_TUK75] = $storedBefore;
        FakeWordPress::respondWith(503, '');

        $figures = tuleva_current_fund_figures(self::TUK75);

        $this->assertSame(self::figures(0.00205, 5747351.0, '2026-09-30', null, null), $figures);
        $this->assertSame([], FakeOptions::$updated);
        $this->assertSame($storedBefore, FakeOptions::$options[self::LAST_GOOD_TUK75]);
    }

    #[Test]
    public function remembersNothingWhenTheFundListDoesNotCarryTheFund(): void
    {
        $this->expectErrorLog();
        $storedBefore = ['managementFeeRate' => 0.00205, 'fundManagerUnits' => 5747351.0, 'fundManagerUnitsDate' => '2026-09-30'];
        FakeOptions::$options[self::LAST_GOOD_TUK75] = $storedBefore;
        FakeWordPress::respondWith(200, json_encode([self::listed(self::TKF100, 0.00178, 202901.49, '2026-09-30')]));

        tuleva_current_fund_figures(self::TUK75);

        $this->assertSame([], FakeOptions::$updated);
        $this->assertSame($storedBefore, FakeOptions::$options[self::LAST_GOOD_TUK75]);
    }

    #[Test]
    public function remembersTheFiguresOfAReadThatListsTheFund(): void
    {
        FakeOptions::$options[self::LAST_GOOD_TUK75] = ['managementFeeRate' => 0.00205, 'fundManagerUnits' => 5747351.0, 'fundManagerUnitsDate' => '2026-09-30'];
        FakeWordPress::respondWith(200, json_encode([self::listed(self::TUK75, 0.00205, 5747351, '2026-10-31', 0.0028, 2)]));

        tuleva_current_fund_figures(self::TUK75);

        $this->assertSame([self::LAST_GOOD_TUK75], FakeOptions::$updated);
        $this->assertSame(
            self::figures(0.00205, 5747351.0, '2026-10-31', 0.0028, 2),
            FakeOptions::$options[self::LAST_GOOD_TUK75]
        );
    }

    #[Test]
    public function ignoresUnitsWhoseDateIsNotARealDate(): void
    {
        foreach ([[2026, 9, 30], '30.09.2026', '2026-02-31'] as $unreadable) {
            $funds = [['isin' => self::TUK75, 'managementFeeRate' => 0.00205, 'fundManagerUnits' => 5747351, 'fundManagerUnitsDate' => $unreadable]];

            $this->assertSame(
                self::figures(0.00205, null, null, null, null),
                tuleva_fund_figures(self::TUK75, $funds, null),
                var_export($unreadable, true)
            );
        }
    }

    #[Test]
    public function hasNoFiguresBeforeTheListHasEverBeenRead(): void
    {
        $this->assertSame(
            self::figures(null, null, null, null, null),
            tuleva_fund_figures(self::TUK75, null, null)
        );
    }

    #[Test]
    public function writesTheManagementFeeAsAPercentageWithAtLeastTwoDecimals(): void
    {
        $this->assertSame('0,205%', tuleva_format_management_fee(0.00205, 'et'));
        $this->assertSame('0,178%', tuleva_format_management_fee(0.00178, 'et'));
        $this->assertSame('0,16%', tuleva_format_management_fee(0.0016, 'et'));
        $this->assertSame('0,20%', tuleva_format_management_fee(0.002, 'et'));
        $this->assertSame('0.205%', tuleva_format_management_fee(0.00205, 'en'));
    }

    #[Test]
    public function writesTheOngoingChargesWithTheDecimalsTheKeyInformationDocumentPrints(): void
    {
        $this->assertSame('0,28%', tuleva_format_ongoing_charges(0.0028, 2, 'et'));
        $this->assertSame('0.28%', tuleva_format_ongoing_charges(0.0028, 2, 'en'));
        $this->assertSame('0,3%', tuleva_format_ongoing_charges(0.003, 1, 'et'));
        $this->assertSame('0.3%', tuleva_format_ongoing_charges(0.003, 1, 'en'));
        $this->assertSame('0,30%', tuleva_format_ongoing_charges(0.003, 2, 'et'));
        $this->assertSame('1%', tuleva_format_ongoing_charges(0.01, 0, 'et'));
        $this->assertSame('1,23%', tuleva_format_ongoing_charges(0.0123, 2, 'et'));
        $this->assertSame('0,28%', tuleva_format_ongoing_charges(0.0028, 2, ''));
    }

    #[Test]
    public function writesTheOngoingChargesLikeTheManagementFeeWhenTheDecimalsAreUnknown(): void
    {
        $this->assertSame('0,28%', tuleva_format_ongoing_charges(0.0028, null, 'et'));
        $this->assertSame('0,295%', tuleva_format_ongoing_charges(0.00295, null, 'et'));
        $this->assertSame('0,20%', tuleva_format_ongoing_charges(0.002, null, 'et'));
        $this->assertSame('0.295%', tuleva_format_ongoing_charges(0.00295, null, 'en'));
    }

    /**
     * As a float, 0.0028 * 100 is 0.27999… and 0.00285 * 100 is 0.28500…03 or 0.28499…, so
     * the figure is rounded as the decimal it is, half up.
     */
    #[Test]
    public function roundsTheOngoingChargesAsTheDecimalTheyAreNotAsAFloat(): void
    {
        $this->assertSame('0,28%', tuleva_format_ongoing_charges(0.0028, 2, 'et'));
        $this->assertSame('0,29%', tuleva_format_ongoing_charges(0.0029, 2, 'et'));
        $this->assertSame('0,57%', tuleva_format_ongoing_charges(0.0057, 2, 'et'));
        $this->assertSame('0,29%', tuleva_format_ongoing_charges(0.00285, 2, 'et'));
        $this->assertSame('0,15%', tuleva_format_ongoing_charges(0.00145, 2, 'et'));
        $this->assertSame('0,11%', tuleva_format_ongoing_charges(0.00105, 2, 'et'));
        $this->assertSame('0,28%', tuleva_format_ongoing_charges(0.0028499, 2, 'et'));
        $this->assertSame('0,3%', tuleva_format_ongoing_charges(0.00295, 1, 'et'));
        $this->assertSame('0,2%', tuleva_format_ongoing_charges(0.00249, 1, 'et'));
    }

    #[Test]
    public function printsAPercentageAsWholeTenThousandthsWithTheDecimalsToShow(): void
    {
        $this->assertSame(['tenThousandths' => 2800, 'decimals' => 2], tuleva_printed_percent(0.0028, 2));
        $this->assertSame(['tenThousandths' => 3000, 'decimals' => 1], tuleva_printed_percent(0.0028, 1));
        $this->assertSame(['tenThousandths' => 2050, 'decimals' => 3], tuleva_printed_percent(0.00205, null));
        $this->assertSame(['tenThousandths' => 1600, 'decimals' => 2], tuleva_printed_percent(0.0016, null));
        $this->assertSame(['tenThousandths' => 1234, 'decimals' => 4], tuleva_printed_percent(0.001234, null));
        $this->assertSame(['tenThousandths' => 10000, 'decimals' => 0], tuleva_printed_percent(0.0123, 0));
        $this->assertSame('1,00%', tuleva_format_management_fee(0.01, 'et'));
    }

    /**
     * The example on 10 000 euros is worked out from the percentage printed beside it, so
     * "0,3%" and "30 euros" always agree, whatever figure the 0,3% was rounded from.
     */
    #[Test]
    public function worksOutAnAmountFromThePrintedPercentage(): void
    {
        $this->assertSame(28, tuleva_euros_at_percent(tuleva_printed_percent(0.0028, 2), 10000));
        $this->assertSame(30, tuleva_euros_at_percent(tuleva_printed_percent(0.0028, 1), 10000));
        $this->assertSame('0,3%', tuleva_format_percent(tuleva_printed_percent(0.0028, 1), 'et'));
        $this->assertSame(29, tuleva_euros_at_percent(tuleva_printed_percent(0.00285, 3), 10000));
        $this->assertSame(21, tuleva_euros_at_percent(tuleva_printed_percent(0.00205, null), 10000));
    }

    #[Test]
    public function writesOneValueForAllFundsWhenTheirOngoingChargesAreTheSame(): void
    {
        $charges = array_fill(0, 4, ['figure' => 0.0028, 'decimals' => 2]);

        $this->assertSame(['0,28%'], tuleva_ongoing_charges_range($charges, 'et'));
        $this->assertSame(['0.28%'], tuleva_ongoing_charges_range($charges, 'en'));
    }

    #[Test]
    public function writesOneValueWhenTheFundsOngoingChargesArePrintedTheSame(): void
    {
        $charges = [['figure' => 0.00281, 'decimals' => 2], ['figure' => 0.00279, 'decimals' => 2]];

        $this->assertSame(['0,28%'], tuleva_ongoing_charges_range($charges, 'et'));
    }

    #[Test]
    public function writesTheLowestAndTheHighestWhenTheFundsOngoingChargesDiffer(): void
    {
        $charges = [
            ['figure' => 0.0028, 'decimals' => 2],
            ['figure' => 0.003, 'decimals' => 1],
            ['figure' => 0.0025, 'decimals' => 2],
            ['figure' => 0.0028, 'decimals' => 2],
        ];

        $this->assertSame(['0,25%', '0,3%'], tuleva_ongoing_charges_range($charges, 'et'));
        $this->assertSame(['0.25%', '0.3%'], tuleva_ongoing_charges_range($charges, 'en'));
    }

    /**
     * When one fund's key information document prints one decimal and the others' print two,
     * the same figure prints as two different percentages.
     */
    #[Test]
    public function writesTheLowestAndTheHighestWhenTheSameFigureIsPrintedWithDifferentDecimals(): void
    {
        $charges = [
            ['figure' => 0.0028, 'decimals' => 2],
            ['figure' => 0.0028, 'decimals' => 2],
            ['figure' => 0.0028, 'decimals' => 2],
            ['figure' => 0.0028, 'decimals' => 1],
        ];

        $this->assertSame(['0,28%', '0,3%'], tuleva_ongoing_charges_range($charges, 'et'));
    }

    /**
     * 0,30% and 0,3% are the same fee, so the sentence about all funds gives it once, in its
     * shorter form, rather than as a range from a value to itself.
     */
    #[Test]
    public function writesOneValueWhenTheFundsPrintTheSameFeeWithDifferentDecimals(): void
    {
        $charges = [
            ['figure' => 0.003, 'decimals' => 2],
            ['figure' => 0.003, 'decimals' => 1],
            ['figure' => 0.003, 'decimals' => 2],
        ];

        $this->assertSame(['0,3%'], tuleva_ongoing_charges_range($charges, 'et'));
        $this->assertSame(['0,3%'], tuleva_ongoing_charges_range(array_reverse($charges), 'et'));
    }

    /**
     * Ordered by the printed percentage, not by the figure behind it: 0.00284 prints as 0,3%
     * with one decimal, above 0.00285 printed as 0,29% with two.
     */
    #[Test]
    public function ordersTheFundsByThePercentageTheyPrint(): void
    {
        $charges = [['figure' => 0.00284, 'decimals' => 1], ['figure' => 0.00285, 'decimals' => 2]];

        $this->assertSame(['0,29%', '0,3%'], tuleva_ongoing_charges_range($charges, 'et'));
    }

    #[Test]
    public function writesNothingForAllFundsWhileAnyFundsOngoingChargesAreUnknown(): void
    {
        $this->assertNull(tuleva_ongoing_charges_range([['figure' => 0.0028, 'decimals' => 2], null], 'et'));
        $this->assertNull(tuleva_ongoing_charges_range([], 'et'));
    }

    #[Test]
    public function writesUnitsWithSpacesBetweenThousandsAndOnlyTheDecimalsThereAre(): void
    {
        $this->assertSame('5 747 351', tuleva_format_units(5747351.0, 'et'));
        $this->assertSame('202 901,49', tuleva_format_units(202901.49, 'et'));
        $this->assertSame('202 901,495', tuleva_format_units(202901.495, 'et'));
        $this->assertSame('202 901.49', tuleva_format_units(202901.49, 'en'));
        $this->assertSame('0', tuleva_format_units(0.0, 'et'));
    }

    #[Test]
    public function roundsUnitsToThreeDecimalsHalfUpAsTheFundRulesDo(): void
    {
        $this->assertSame('202 901,492', tuleva_format_units(202901.49203, 'et'));
        $this->assertSame('202 901,493', tuleva_format_units(202901.4925, 'et'));
        $this->assertSame('202 901,492', tuleva_format_units(202901.4924, 'et'));
        $this->assertSame('1,001', tuleva_format_units(1.0005, 'et'));
        $this->assertSame('2', tuleva_format_units(1.9996, 'et'));
        $this->assertSame('5 747 351', tuleva_format_units(5747351.0004, 'et'));
        $this->assertSame('202 901.492', tuleva_format_units(202901.49203, 'en'));
    }

    #[Test]
    public function writesEstonianNumbersWhenThePageLanguageIsUnknown(): void
    {
        $this->assertSame('0,205%', tuleva_format_management_fee(0.00205, ''));
        $this->assertSame('202 901,49', tuleva_format_units(202901.49, ''));
    }

    #[Test]
    public function writesTheUnitsDateTheWayThePagesWriteDates(): void
    {
        $this->assertSame('30.09.2026', tuleva_format_figures_date('2026-09-30'));
    }

    private static function listed(
        string $isin,
        float $fee,
        float $units,
        string $date,
        float $ongoingCharges = 0.0028,
        mixed $decimals = 2
    ): array {
        return [
            'isin' => $isin,
            'managementFeeRate' => $fee,
            'fundManagerUnits' => $units,
            'fundManagerUnitsDate' => $date,
            'ongoingChargesFigure' => $ongoingCharges,
            'ongoingChargesPercentDecimals' => $decimals,
        ];
    }

    private static function figures(?float $fee, ?float $units, ?string $date, ?float $ongoingCharges, ?int $decimals): array
    {
        return [
            'managementFeeRate' => $fee,
            'fundManagerUnits' => $units,
            'fundManagerUnitsDate' => $date,
            'ongoingChargesFigure' => $ongoingCharges,
            'ongoingChargesPercentDecimals' => $decimals,
        ];
    }
}
