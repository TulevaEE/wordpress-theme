<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

require_once __DIR__ . '/../support/FakeWordPress.php';
require_once __DIR__ . '/../../helpers/funds-api.php';

final class FundsApiTest extends TestCase
{
    protected function setUp(): void
    {
        FakeWordPress::reset();
    }

    #[Test]
    public function returnsDecodedFunds(): void
    {
        FakeWordPress::respondWith(200, '[{"isin":"EE3600109435","nav":1.28834}]');

        $funds = get_funds_from_api();

        $this->assertCount(1, $funds);
        $this->assertSame('EE3600109435', $funds[0]['isin']);
        $this->assertEqualsWithDelta(1.28834, $funds[0]['nav'], 0.00001);
    }

    #[Test]
    public function requestsTheUnfilteredFundListByDefault(): void
    {
        FakeWordPress::respondWith(200, '[]');

        get_funds_from_api();

        $this->assertSame(
            'https://onboarding-service.tuleva.ee/v1/funds',
            FakeWordPress::$requests[0]['url']
        );
    }

    #[Test]
    public function filtersByFundManagerWhenGiven(): void
    {
        FakeWordPress::respondWith(200, '[]');

        get_funds_from_api('Tuleva');

        $this->assertSame(
            'https://onboarding-service.tuleva.ee/v1/funds?fundManager.name=Tuleva',
            FakeWordPress::$requests[0]['url']
        );
    }

    #[Test]
    public function requestsWithAnExplicitTimeout(): void
    {
        FakeWordPress::respondWith(200, '[]');

        get_funds_from_api();

        $this->assertSame(3, FakeWordPress::$requests[0]['args']['timeout']);
    }

    #[Test]
    public function returnsNullWhenTheRequestFails(): void
    {
        FakeWordPress::$response = new WP_Error();

        $this->assertNull(get_funds_from_api());
    }

    #[Test]
    public function returnsNullOnAnErrorResponseCode(): void
    {
        FakeWordPress::respondWith(503, 'Service Unavailable');

        $this->assertNull(get_funds_from_api());
    }

    #[Test]
    public function returnsNullOnAnUnparseableBody(): void
    {
        FakeWordPress::respondWith(200, '<html>gateway timeout</html>');

        $this->assertNull(get_funds_from_api());
    }

    #[Test]
    public function distinguishesAnEmptyFundListFromAFailure(): void
    {
        FakeWordPress::respondWith(200, '[]');

        $this->assertSame([], get_funds_from_api());
    }

    #[Test]
    public function servesRepeatCallsFromTheCache(): void
    {
        FakeWordPress::respondWith(200, '[{"isin":"EE3600109435"}]');

        get_funds_from_api('Tuleva');
        $funds = get_funds_from_api('Tuleva');

        $this->assertSame([['isin' => 'EE3600109435']], $funds);
        $this->assertCount(1, FakeWordPress::$requests);
    }

    #[Test]
    public function cachesFailuresSoAnOutageIsNotRetriedOnEveryRequest(): void
    {
        FakeWordPress::respondWith(503, '');

        $this->assertNull(get_funds_from_api());
        $this->assertNull(get_funds_from_api());
        $this->assertCount(1, FakeWordPress::$requests);
    }

    #[Test]
    public function cachesEachFundManagerSeparately(): void
    {
        FakeWordPress::respondWith(200, '[{"isin":"EE3600109435"}]');
        get_funds_from_api();

        FakeWordPress::respondWith(200, '[{"isin":"EE3600001707"}]');
        $filtered = get_funds_from_api('Tuleva');

        $this->assertSame([['isin' => 'EE3600001707']], $filtered);
        $this->assertCount(2, FakeWordPress::$requests);
    }
}
