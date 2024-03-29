<?php

namespace App\Containers\Core\Authorization\Tasks;

use App\Containers\Core\Authorization\Data\Repositories\RoleRepository;
use App\Ship\Parents\Tasks\Task;

class GetAllRolesTask extends Task
{
    protected RoleRepository $repository;

    public function __construct(RoleRepository $repository)
    {
        $this->repository = $repository;
    }

    public function run(bool $skipPagination = false)
    {
        return $skipPagination ? $this->repository->all() : $this->repository->paginate();
    }
}
