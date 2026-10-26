<?php
declare(strict_types=1);

if (!defined('MINUTE_IN_SECONDS')) {
    define('MINUTE_IN_SECONDS', 60);
}

if (!defined('DAY_IN_SECONDS')) {
    define('DAY_IN_SECONDS', 86400);
}

/**
 * Stands in for the WordPress transient cache and HTTP API, neither of which is
 * loaded when the helper tests run outside WordPress.
 */
final class FakeWordPress
{
    public static array $transients = [];
    public static array $transientLifetimes = [];
    public static array $requests = [];
    public static $response = null;

    public static function reset(): void
    {
        self::$transients = [];
        self::$transientLifetimes = [];
        self::$requests = [];
        self::$response = null;
    }

    public static function respondWith(int $code, string $body, array $headers = []): void
    {
        self::$response = ['response' => ['code' => $code], 'body' => $body, 'headers' => $headers];
    }
}

class WP_Error
{
    public function get_error_message(): string
    {
        return 'cURL error 28: Connection timed out';
    }
}

function get_transient($key)
{
    return FakeWordPress::$transients[$key] ?? false;
}

function set_transient($key, $value, $expiration)
{
    FakeWordPress::$transients[$key] = $value;
    FakeWordPress::$transientLifetimes[$key] = $expiration;

    return true;
}

function wp_remote_get($url, $args = [])
{
    FakeWordPress::$requests[] = ['method' => 'GET', 'url' => $url, 'args' => $args];

    return FakeWordPress::$response;
}

function wp_remote_head($url, $args = [])
{
    FakeWordPress::$requests[] = ['method' => 'HEAD', 'url' => $url, 'args' => $args];

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

function wp_remote_retrieve_header($response, $header)
{
    return $response['headers'][strtolower($header)] ?? '';
}
