<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

if (!defined('MINUTE_IN_SECONDS')) {
    define('MINUTE_IN_SECONDS', 60);
}

/**
 * Stands in for the WordPress transient cache and HTTP API, neither of which is
 * loaded when the helper tests run outside WordPress.
 */
final class FakeWordPress
{
    public static array $transients = [];
    public static array $requests = [];
    public static $response = null;

    public static function reset(): void
    {
        self::$transients = [];
        self::$requests = [];
        self::$response = null;
    }

    public static function respondWith(int $code, string $body): void
    {
        self::$response = ['response' => ['code' => $code], 'body' => $body];
    }
}

class WP_Error
{
}

function get_transient($key)
{
    return FakeWordPress::$transients[$key] ?? false;
}

function set_transient($key, $value, $expiration)
{
    FakeWordPress::$transients[$key] = $value;

    return true;
}

function wp_remote_get($url, $args = [])
{
    FakeWordPress::$requests[] = ['url' => $url, 'args' => $args];

    return FakeWordPress::$response;
}

function is_wp_error($thing)
{
    return $thing instanceof WP_Error;
}

function wp_remote_retrieve_response_code($response)
{
    return $response['response']['code'] ?? '';
}

function wp_remote_retrieve_body($response)
{
    return $response['body'] ?? '';
}

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

        $this->assertSame([['isin' => 'EE3600109435', 'nav' => 1.28834]], $funds);
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
