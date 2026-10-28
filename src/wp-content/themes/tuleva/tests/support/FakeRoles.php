<?php
declare(strict_types=1);

/**
 * Stands in for the WordPress roles API, which is not loaded when the tests run outside
 * WordPress. Every test that needs roles requires this one file: PHPUnit loads all test
 * files into one process, and a function can be declared only once.
 */
final class FakeRoles
{
    public static array $roles = [];
    public static int $added = 0;

    public static function reset(): void
    {
        self::$roles = [];
        self::$added = 0;
    }
}

function get_role($role)
{
    return isset(FakeRoles::$roles[$role]) ? (object) FakeRoles::$roles[$role] : null;
}

function add_role($role, $display_name, $capabilities = [])
{
    FakeRoles::$roles[$role] = ['name' => $display_name, 'capabilities' => $capabilities];
    FakeRoles::$added++;

    return (object) FakeRoles::$roles[$role];
}
