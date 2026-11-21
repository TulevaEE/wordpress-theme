<?php
declare(strict_types=1);

/**
 * Stands in for the WordPress options table, which is not loaded when the tests run
 * outside WordPress. Every test that needs options requires this one file: PHPUnit loads
 * all test files into one process, and a function can be declared only once.
 */
final class FakeOptions
{
    public static array $options = [];

    /** @var list<string> the options written, in order, one entry per update_option() call */
    public static array $updated = [];

    public static function reset(): void
    {
        self::$options = [];
        self::$updated = [];
    }
}

function get_option($name, $default = false)
{
    return FakeOptions::$options[$name] ?? $default;
}

function update_option($name, $value, $autoload = null)
{
    FakeOptions::$options[$name] = $value;
    FakeOptions::$updated[] = $name;

    return true;
}
