<?php
declare(strict_types=1);

/**
 * Stands in for WordPress's translation functions, which are not loaded when the tests run
 * outside WordPress. A string is its own translation, as on an English page, unless a test
 * loads a compiled catalogue (lang/et.mo) to render a page in Estonian; such a test resets it
 * afterwards. Every test that translates requires this one file: PHPUnit loads all test files
 * into one process, and a function can be declared only once. Guarded, so a branch with its
 * own stubs merges either way round.
 */
final class FakeTranslations
{
    /** @var array<string, string> msgid, prefixed with its context and "\x04" when it has one, to msgstr */
    public static array $catalogue = [];

    public static function reset(): void
    {
        self::$catalogue = [];
    }

    /**
     * Reads a little-endian .mo file, as the theme's lang/*.mo files are written.
     */
    public static function load(string $moFile): void
    {
        $data = (string) file_get_contents($moFile);
        [, , $count, $originals, $translations] = array_values(unpack('V7', substr($data, 0, 28)));

        self::$catalogue = [];
        for ($i = 0; $i < $count; $i++) {
            [$originalLength, $originalOffset] = array_values(unpack('V2', substr($data, $originals + 8 * $i, 8)));
            [$translationLength, $translationOffset] = array_values(unpack('V2', substr($data, $translations + 8 * $i, 8)));
            self::$catalogue[substr($data, $originalOffset, $originalLength)] = substr($data, $translationOffset, $translationLength);
        }
    }
}

if (!function_exists('__')) {
    function __($text, $domain = null)
    {
        return FakeTranslations::$catalogue[$text] ?? $text;
    }
}

if (!function_exists('_e')) {
    function _e($text, $domain = null)
    {
        echo __($text, $domain);
    }
}

if (!function_exists('_x')) {
    function _x($text, $context, $domain = null)
    {
        return FakeTranslations::$catalogue[$context . "\x04" . $text] ?? $text;
    }
}

if (!function_exists('esc_html__')) {
    function esc_html__($text, $domain = null)
    {
        return htmlspecialchars((string) __($text, $domain), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('esc_attr__')) {
    function esc_attr__($text, $domain = null)
    {
        return htmlspecialchars((string) __($text, $domain), ENT_QUOTES, 'UTF-8');
    }
}
