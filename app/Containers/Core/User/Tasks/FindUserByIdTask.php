<?php

namespace App\Containers\Core\User\Tasks;

use App\Containers\Core\User\Data\Repositories\UserRepository;
use App\Containers\Core\User\Models\User;
use App\Ship\Exceptions\NotFoundException;
use App\Ship\Parents\Tasks\Task;
use Exception;

class FindUserByIdTask extends Task
{
    protected UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function run($userId): User
    {
        try {
            $user = $this->repository->find($userId);
        } catch (Exception $e) {
            throw new NotFoundException();
        }

        return $user;
    }
}
