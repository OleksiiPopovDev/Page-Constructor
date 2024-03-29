<?php

namespace App\Containers\Core\Authorization\Tasks;

use App\Containers\Core\Authorization\Data\Repositories\PermissionRepository;
use App\Ship\Parents\Tasks\Task;

class GetAllPermissionsTask extends Task
{
    protected PermissionRepository $repository;

    public function __construct(PermissionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function run(bool $skipPagination = false)
    {
        return $skipPagination ? $this->repository->all() : $this->repository->paginate();
    }
}
