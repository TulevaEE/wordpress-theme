<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

require_once __DIR__ . '/../support/FakeOptions.php';
require_once __DIR__ . '/../../helpers/average-fund-fee.php';

final class AverageFundFeeTest extends TestCase
{
    protected function setUp(): void
    {
        FakeOptions::reset();
    }

    #[Test]
    public function weightsEachSecondPillarFundsOngoingChargesByItsVolume(): void
    {
        $funds = [
            self::fund('Expensive', 2, 0.0100, 100.0),
            self::fund('Cheap', 2, 0.0020, 300.0),
        ];

        $this->assertSame(0.004, tuleva_weighted_average_fund_fee($funds));
    }

    #[Test]
    public function countsTulevasOwnFundsLikeAnyOther(): void
    {
        $funds = [
            self::fund('Other', 2, 0.0100, 100.0, 'LHV'),
            self::fund('Tuleva', 2, 0.0028, 100.0, 'Tuleva'),
        ];

        $this->assertSame(0.0064, tuleva_weighted_average_fund_fee($funds));
    }

    #[Test]
    public function leavesOutThirdPillarAndInactiveFunds(): void
    {
        $funds = [
            self::fund('Second pillar', 2, 0.0050, 100.0),
            self::fund('Third pillar', 3, 0.0150, 900.0),
            self::fund('Closed', 2, 0.0150, 900.0, 'LHV', 'INACTIVE'),
        ];

        $this->assertSame(0.005, tuleva_weighted_average_fund_fee($funds));
    }

    #[Test]
    public function roundsToTheHundredthOfAPercentTheCalculatorShows(): void
    {
        $funds = [
            self::fund('A', 2, 0.0148, 867357.0),
            self::fund('B', 2, 0.0029, 906691.0),
        ];

        $this->assertSame(0.0087, tuleva_weighted_average_fund_fee($funds));
    }

    #[Test]
    public function leavesOutAFundWithoutAFeeOrAVolumeInsteadOfGivingUp(): void
    {
        $this->expectErrorLog();

        $funds = [
            self::fund('Known', 2, 0.0050, 100.0),
            ['isin' => 'XX0000000001', 'status' => 'ACTIVE', 'pillar' => 2, 'ongoingChargesFigure' => 0.0150, 'volume' => null],
            ['isin' => 'XX0000000002', 'status' => 'ACTIVE', 'pillar' => 2, 'ongoingChargesFigure' => null, 'volume' => 900.0],
            self::fund('New', 2, 0.0150, 0.0),
        ];

        $this->assertSame(0.005, tuleva_weighted_average_fund_fee($funds));
    }

    #[Test]
    public function givesNoAverageWithoutSecondPillarFunds(): void
    {
        $this->assertNull(tuleva_weighted_average_fund_fee([self::fund('Third', 3, 0.01, 100.0)]));
    }

    #[Test]
    public function reusesThisMonthsAverageWithoutReadingTheFunds(): void
    {
        FakeOptions::$options['tuleva_average_fund_fee'] = ['month' => '2026-10', 'fee' => 0.0074];

        $fee = tuleva_monthly_average_fund_fee(
            function () {
                throw new LogicException('The funds must not be read again this month');
            },
            '2026-10'
        );

        $this->assertSame(0.0074, $fee);
    }

    #[Test]
    public function recalculatesAndStoresTheAverageInANewMonth(): void
    {
        FakeOptions::$options['tuleva_average_fund_fee'] = ['month' => '2026-09', 'fee' => 0.0077];

        $fee = tuleva_monthly_average_fund_fee(
            fn () => [self::fund('Only', 2, 0.0074, 100.0)],
            '2026-10'
        );

        $this->assertSame(0.0074, $fee);
        $this->assertSame(
            ['month' => '2026-10', 'fee' => 0.0074],
            FakeOptions::$options['tuleva_average_fund_fee']
        );
    }

    #[Test]
    public function keepsLastMonthsAverageWhileTheFundsCannotBeRead(): void
    {
        $this->expectErrorLog();

        FakeOptions::$options['tuleva_average_fund_fee'] = ['month' => '2026-09', 'fee' => 0.0077];

        $fee = tuleva_monthly_average_fund_fee(fn () => null, '2026-10');

        $this->assertSame(0.0077, $fee);
        $this->assertSame(
            ['month' => '2026-09', 'fee' => 0.0077],
            FakeOptions::$options['tuleva_average_fund_fee']
        );
    }

    #[Test]
    public function recalculatesWhenThisMonthsStoredAverageHasNoUsableFee(): void
    {
        FakeOptions::$options['tuleva_average_fund_fee'] = ['month' => '2026-10', 'fee' => 'n/a'];

        $fee = tuleva_monthly_average_fund_fee(fn () => [self::fund('Only', 2, 0.0074, 100.0)], '2026-10');

        $this->assertSame(0.0074, $fee);
    }

    #[Test]
    public function hasNoAverageBeforeTheFundsHaveEverBeenRead(): void
    {
        $this->expectErrorLog();

        $this->assertNull(tuleva_monthly_average_fund_fee(fn () => null, '2026-10'));
    }

    #[Test]
    public function formatsTheFeeAsThePageLanguageWritesPercentages(): void
    {
        $this->assertSame('0,77%', tuleva_format_fee_percent(0.0077, 'et'));
        $this->assertSame('0.77%', tuleva_format_fee_percent(0.0077, 'en'));
        $this->assertSame('0,16%', tuleva_format_fee_percent(0.0016, 'et'));
    }

    private static function fund(
        string $name,
        int $pillar,
        float $ongoingCharges,
        float $volume,
        string $manager = 'LHV',
        string $status = 'ACTIVE'
    ): array {
        return [
            'name' => $name,
            'status' => $status,
            'pillar' => $pillar,
            'fundManager' => ['name' => $manager],
            'ongoingChargesFigure' => $ongoingCharges,
            'volume' => $volume,
        ];
    }
}
