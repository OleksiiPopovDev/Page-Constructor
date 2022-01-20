<?php

namespace App\Containers\Core\Authorization\Tasks;

use App\Containers\Core\Authorization\Data\Repositories\RoleRepository;
use App\Containers\Core\Authorization\Models\Role;
use App\Ship\Exceptions\CreateResourceFailedException;
use App\Ship\Parents\Tasks\Task;
use Exception;

class CreateRoleTask extends Task
{
    protected RoleRepository $repository;

    public function __construct(RoleRepository $repository)
    {
        $this->repository = $repository;
    }

    public function run(string $name, string $description = null, string $displayName = null, int $level = 0): Role
    {
        app()['cache']->forget('spatie.permission.cache');

        try {
            $role = $this->repository->create([
                'name' => strtolower($name),
                'description' => $description,
                'display_name' => $displayName,
                'guard_name' => 'web',
                'level' => $level,
            ]);
        } catch (Exception $exception) {
            throw new CreateResourceFailedException();
        }

        return $role;
    }
}
