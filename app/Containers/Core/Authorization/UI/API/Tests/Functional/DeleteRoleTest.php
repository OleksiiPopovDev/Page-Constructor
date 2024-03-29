<?php

namespace App\Containers\Core\Authorization\UI\API\Tests\Functional;

use App\Containers\Core\Authorization\Models\Role;
use App\Containers\Core\Authorization\Tests\ApiTestCase;

/**
 * Class DeleteRoleTest.
 *
 * @group authorization
 * @group api
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class DeleteRoleTest extends ApiTestCase
{
    protected string $endpoint = 'delete@v1/roles/{id}';

    protected array $access = [
        'roles' => '',
        'permissions' => 'manage-roles',
    ];

    public function testDeleteExistingRole(): void
    {
        $role = Role::factory()->create();

        $response = $this->injectId($role->id)->makeCall();

        $response->assertStatus(204);
    }
}
