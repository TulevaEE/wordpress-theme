<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

require_once __DIR__ . '/../support/FakeRoles.php';
require_once __DIR__ . '/../../helpers/access-reviewer.php';

final class AccessReviewerRoleTest extends TestCase
{
    protected function setUp(): void
    {
        FakeRoles::reset();
    }

    #[Test]
    public function theAccessReviewerCanListUsersAndNothingElse(): void
    {
        tuleva_register_access_reviewer_role();

        $this->assertSame(
            ['name' => 'Access reviewer', 'capabilities' => ['read' => true, 'list_users' => true]],
            FakeRoles::$roles['access_reviewer']
        );
    }

    #[Test]
    public function theRoleIsAddedOnceAndNotRewrittenOnEveryRequest(): void
    {
        tuleva_register_access_reviewer_role();
        tuleva_register_access_reviewer_role();

        $this->assertSame(1, FakeRoles::$added);
    }

    #[Test]
    public function theUserListingGivesLoginEmailAndRolesAndNothingElse(): void
    {
        $users = [
            (object) ['user_login' => 'access-review', 'user_email' => 'reviewer@example.com', 'roles' => ['access_reviewer'], 'user_pass' => 'hash'],
            (object) ['user_login' => 'editor', 'user_email' => 'editor@example.com', 'roles' => [1 => 'editor'], 'user_pass' => 'hash'],
        ];

        $this->assertSame(
            [
                ['login' => 'access-review', 'email' => 'reviewer@example.com', 'roles' => ['access_reviewer']],
                ['login' => 'editor', 'email' => 'editor@example.com', 'roles' => ['editor']],
            ],
            tuleva_access_review_users($users)
        );
    }
}
