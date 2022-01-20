<?php

namespace App\Containers\Core\User\Tasks;

use App\Containers\Core\User\Data\Repositories\UserRepository;
use App\Containers\Core\User\Models\User;
use App\Ship\Exceptions\DeleteResourceFailedException;
use App\Ship\Parents\Tasks\Task;
use Exception;

class DeleteUserTask extends Task
{
    protected UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function run(User $user): ?int
    {
        try {
            return $this->repository->delete($user->id);
        } catch (Exception $exception) {
            throw new DeleteResourceFailedException();
        }
    }
}
