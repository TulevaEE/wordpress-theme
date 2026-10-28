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
}
