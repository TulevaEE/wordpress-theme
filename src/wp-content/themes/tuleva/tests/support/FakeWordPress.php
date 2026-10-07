<?php
declare(strict_types=1);

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
