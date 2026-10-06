<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

require_once __DIR__ . '/../../helpers/fund-figures.php';

final class FundFiguresTest extends TestCase
{
    private const TKF100 = 'EE0000003283';
    private const TUK75 = 'EE3600109435';

    #[Test]
    public function readsAFundsManagementFeeAndManagerUnitsFromTheFundList(): void
    {
        $funds = [
            self::listed(self::TUK75, 0.00205, 5747351, '2026-09-30'),
            self::listed(self::TKF100, 0.00178, 202901.49, '2026-09-30'),
        ];

        $this->assertSame(
            ['managementFeeRate' => 0.00178, 'fundManagerUnits' => 202901.49, 'fundManagerUnitsDate' => '2026-09-30'],
            tuleva_fund_figures(self::TKF100, $funds, null)
        );
    }

    #[Test]
    public function showsTheLastFiguresWhileTheListCannotBeRead(): void
    {
        $lastGood = ['managementFeeRate' => 0.00205, 'fundManagerUnits' => 5747351.0, 'fundManagerUnitsDate' => '2026-09-30'];

        $this->assertSame($lastGood, tuleva_fund_figures(self::TUK75, null, $lastGood));
    }

    #[Test]
    public function showsTheLastFiguresWhenTheListNoLongerCarriesTheFund(): void
    {
        $lastGood = ['managementFeeRate' => 0.00205, 'fundManagerUnits' => 5747351.0, 'fundManagerUnitsDate' => '2026-09-30'];

        $this->assertSame($lastGood, tuleva_fund_figures(self::TUK75, [self::listed(self::TKF100, 0.00178, 1.0, '2026-09-30')], $lastGood));
    }

    #[Test]
    public function keepsTheLastUnitsWhenTheListHasNoneForTheFund(): void
    {
        $lastGood = ['managementFeeRate' => 0.00205, 'fundManagerUnits' => 5747351.0, 'fundManagerUnitsDate' => '2026-08-31'];
        $withoutUnits = [['isin' => self::TUK75, 'managementFeeRate' => 0.00163]];

        $this->assertSame(
            ['managementFeeRate' => 0.00163, 'fundManagerUnits' => 5747351.0, 'fundManagerUnitsDate' => '2026-08-31'],
            tuleva_fund_figures(self::TUK75, $withoutUnits, $lastGood)
        );
    }

    #[Test]
    public function ignoresUnitsWhoseDateIsNotARealDate(): void
    {
        foreach ([[2026, 9, 30], '30.09.2026', '2026-02-31'] as $unreadable) {
            $funds = [['isin' => self::TUK75, 'managementFeeRate' => 0.00205, 'fundManagerUnits' => 5747351, 'fundManagerUnitsDate' => $unreadable]];

            $this->assertSame(
                ['managementFeeRate' => 0.00205, 'fundManagerUnits' => null, 'fundManagerUnitsDate' => null],
                tuleva_fund_figures(self::TUK75, $funds, null),
                var_export($unreadable, true)
            );
        }
    }

    #[Test]
    public function hasNoFiguresBeforeTheListHasEverBeenRead(): void
    {
        $this->assertSame(
            ['managementFeeRate' => null, 'fundManagerUnits' => null, 'fundManagerUnitsDate' => null],
            tuleva_fund_figures(self::TUK75, null, null)
        );
    }

    #[Test]
    public function writesTheManagementFeeAsAPercentageWithAtLeastTwoDecimals(): void
    {
        $this->assertSame('0,205%', tuleva_format_management_fee(0.00205, 'et'));
        $this->assertSame('0,178%', tuleva_format_management_fee(0.00178, 'et'));
        $this->assertSame('0,16%', tuleva_format_management_fee(0.0016, 'et'));
        $this->assertSame('0.205%', tuleva_format_management_fee(0.00205, 'en'));
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

    private static function listed(string $isin, float $fee, float $units, string $date): array
    {
        return [
            'isin' => $isin,
            'managementFeeRate' => $fee,
            'fundManagerUnits' => $units,
            'fundManagerUnitsDate' => $date,
        ];
    }
}
