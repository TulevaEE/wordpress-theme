<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

require_once __DIR__ . '/../support/FakeWordPress.php';
require_once __DIR__ . '/../support/FakeOptions.php';
require_once __DIR__ . '/../../helpers/funds-api.php';
require_once __DIR__ . '/../../helpers/counts.php';

final class CountsTest extends TestCase
{
    protected function setUp(): void
    {
        FakeWordPress::reset();
        FakeOptions::reset();
    }

    #[Test]
    public function countsSaversFromTheInvestorCountEndpoint(): void
    {
        FakeWordPress::respondWith(200, '{"count":86787}');

        $this->assertSame(86787, get_investor_count());
        $this->assertSame(
            [['GET', 'https://onboarding-service.tuleva.ee/v1/statistics/investor-count']],
            self::requests()
        );
    }

    #[Test]
    public function countsMembersFromTheTotalTheMembersEndpointGivesAHeadRequest(): void
    {
        FakeWordPress::respondWith(200, '', ['x-total-count' => '9810']);

        $this->assertSame(9810, get_member_count());
        $this->assertSame([['HEAD', 'https://onboarding-service.tuleva.ee/v1/members']], self::requests());
    }

    #[Test]
    public function asksForTheMemberCountOnceADay(): void
    {
        FakeWordPress::respondWith(200, '', ['x-total-count' => '9810']);

        get_member_count();

        $this->assertSame(9810, get_member_count());
        $this->assertCount(1, FakeWordPress::$requests);
        $this->assertSame(DAY_IN_SECONDS, FakeWordPress::$transientLifetimes['tuleva_member_count']);
    }

    #[Test]
    public function keepsTheLastMemberCountWhileTheEndpointFails(): void
    {
        FakeOptions::$options['tuleva_member_count_last_good'] = 9810;
        FakeWordPress::respondWith(503, '');
        $this->expectErrorLog();

        $this->assertSame(9810, get_member_count());
    }

    #[Test]
    public function keepsTheLastMemberCountWhenTheTotalIsImplausible(): void
    {
        FakeOptions::$options['tuleva_member_count_last_good'] = 9810;
        FakeWordPress::respondWith(200, '', ['x-total-count' => '12']);
        $this->expectErrorLog();

        $this->assertSame(9810, get_member_count());
    }

    #[Test]
    public function remembersAGoodCountForTheNextFailure(): void
    {
        FakeWordPress::respondWith(200, '', ['x-total-count' => '9810']);

        get_member_count();

        $this->assertSame(9810, FakeOptions::$options['tuleva_member_count_last_good']);
    }

    #[Test]
    public function asksAgainOnlyAfterFiveMinutesWhenTheEndpointFails(): void
    {
        FakeOptions::$options['tuleva_member_count_last_good'] = 9810;
        FakeWordPress::respondWith(503, '');
        $this->expectErrorLog();

        get_member_count();

        $this->assertSame(9810, get_member_count());
        $this->assertCount(1, FakeWordPress::$requests);
        $this->assertSame(0, FakeWordPress::$transients['tuleva_member_count']);
        $this->assertSame(5 * MINUTE_IN_SECONDS, FakeWordPress::$transientLifetimes['tuleva_member_count']);
    }

    #[Test]
    public function keepsTheLastSaverCountWhenTheRequestCannotBeMade(): void
    {
        FakeOptions::$options['tuleva_investor_count_last_good'] = 86787;
        FakeWordPress::$response = new WP_Error();
        $this->expectErrorLog();

        $this->assertSame(86787, get_investor_count());
    }

    #[Test]
    public function claimsTheCountInWholeThousandsRoundedDownSoOverStaysTrue(): void
    {
        $this->assertSame("86\u{00A0}000", tuleva_format_count_over(86787, 'et'));
    }

    #[Test]
    public function groupsThousandsWithACommaInEnglish(): void
    {
        $this->assertSame('9,000', tuleva_format_count_over(9810, 'en'));
    }

    #[Test]
    public function claimsTheThousandBelowWhenTheCountIsARoundThousand(): void
    {
        $this->assertSame("85\u{00A0}000", tuleva_format_count_over(86000, 'et'));
    }

    #[Test]
    public function claimsNothingWithoutACount(): void
    {
        $this->assertNull(tuleva_format_count_over(0, 'et'));
    }

    #[Test]
    public function claimsAFundsSaversFromTheFundList(): void
    {
        FakeWordPress::respondWith(200, self::THIRD_PILLAR_FUND_LIST);

        $this->assertSame("74\u{00A0}000", tuleva_fund_saver_count_over(self::TUV100, 'et'));
    }

    #[Test]
    public function claimsAFundsVolumeInMillionsRoundedDownToTens(): void
    {
        FakeWordPress::respondWith(200, self::THIRD_PILLAR_FUND_LIST);

        $this->assertSame('600', tuleva_fund_volume_millions_over(self::TUV100, 'et'));
    }

    #[Test]
    public function groupsAVolumeOfBillionsInMillions(): void
    {
        FakeWordPress::respondWith(200, '[{"isin":"EE3600109435","volume":1177727624.14,"peopleCount":37737}]');

        $this->assertSame("1\u{00A0}170", tuleva_fund_volume_millions_over('EE3600109435', 'et'));
    }

    #[Test]
    public function claimsNothingForAFundTheListDoesNotCountSaversFor(): void
    {
        FakeWordPress::respondWith(200, '[{"isin":"EE3600001707","volume":604852355.82,"peopleCount":0}]');

        $this->assertNull(tuleva_fund_saver_count_over(self::TUV100, 'et'));
    }

    #[Test]
    public function readsNoFundFiguresFromTheProductionApiInLocalDevelopment(): void
    {
        $_SERVER['SERVER_NAME'] = 'localhost';
        FakeWordPress::respondWith(200, self::THIRD_PILLAR_FUND_LIST);

        try {
            $this->assertNull(tuleva_fund_saver_count_over(self::TUV100, 'et'));
            $this->assertSame([], FakeWordPress::$requests);
        } finally {
            unset($_SERVER['SERVER_NAME']);
        }
    }

    #[Test]
    public function claimsNothingForAFundMissingFromTheList(): void
    {
        FakeWordPress::respondWith(200, self::THIRD_PILLAR_FUND_LIST);

        $this->assertNull(tuleva_fund_volume_millions_over('EE0000003283', 'et'));
    }

    private const TUV100 = 'EE3600001707';
    private const THIRD_PILLAR_FUND_LIST = '[{"isin":"EE3600001707","volume":604852355.82,"peopleCount":74292}]';

    private static function requests(): array
    {
        return array_map(fn(array $request) => [$request['method'], $request['url']], FakeWordPress::$requests);
    }
}
