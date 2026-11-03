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

    /** How many times a stored role's capabilities were changed. */
    public static int $changed = 0;

    public static function reset(): void
    {
        self::$roles = [];
        self::$added = 0;
        self::$changed = 0;
    }
}

/**
 * A stored role, as WP_Role: changing a capability writes it back to the store.
 */
final class FakeRole
{
    public function __construct(public string $name, public array $capabilities)
    {
    }

    public function add_cap($cap, $grant = true): void
    {
        $this->capabilities[$cap] = $grant;
        $this->store();
    }

    public function remove_cap($cap): void
    {
        unset($this->capabilities[$cap]);
        $this->store();
    }

    private function store(): void
    {
        FakeRoles::$roles[$this->name]['capabilities'] = $this->capabilities;
        FakeRoles::$changed++;
    }
}

function get_role($role)
{
    return isset(FakeRoles::$roles[$role]) ? new FakeRole($role, FakeRoles::$roles[$role]['capabilities']) : null;
}

function add_role($role, $display_name, $capabilities = [])
{
    FakeRoles::$roles[$role] = ['name' => $display_name, 'capabilities' => $capabilities];
    FakeRoles::$added++;

    return new FakeRole($role, $capabilities);
}
