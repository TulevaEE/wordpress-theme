<?php
declare(strict_types=1);

/**
 * Stands in for the media library, which is not loaded when the tests run outside
 * WordPress. Every test that turns an attachment id into its URL requires this one file:
 * PHPUnit loads all test files into one process, and a function can be declared only once.
 */
final class FakeAttachments
{
    /** @var array<int, string> */
    public static array $urls = [];

    public static function reset(): void
    {
        self::$urls = [];
    }
}

function wp_get_attachment_url($attachment_id)
{
    return FakeAttachments::$urls[$attachment_id] ?? false;
}
