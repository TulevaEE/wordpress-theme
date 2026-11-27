<?php
declare(strict_types=1);

/**
 * Stands in for WordPress's filters, which are not loaded when the tests run outside
 * WordPress. A hook gives back the value it is handed unless a test file registers what it
 * answers on production. Every test that applies filters requires this one file: PHPUnit
 * loads all test files into one process, and a function can be declared only once.
 */
final class FakeFilters
{
    /** @var array<string, callable> what a hook answers, given its value and its other arguments */
    public static array $filters = [];
}

function apply_filters($hook, $value, ...$args)
{
    $filter = FakeFilters::$filters[$hook] ?? null;

    return $filter === null ? $value : $filter($value, ...$args);
}
