<?php

namespace App\Containers\Core\Authorization\Actions;

use App\Containers\Core\Authorization\Tasks\DeleteRoleTask;
use App\Containers\Core\Authorization\Tasks\FindRoleTask;
use App\Containers\Core\Authorization\UI\API\Requests\DeleteRoleRequest;
use App\Ship\Parents\Actions\Action;
use Spatie\Permission\Contracts\Role;

class DeleteRoleAction extends Action
{
    public function run(DeleteRoleRequest $request): Role
    {
        $role = app(FindRoleTask::class)->run($request->id);
        app(DeleteRoleTask::class)->run($role);

        return $role;
    }
}
