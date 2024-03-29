<?php

namespace App\Containers\Core\User\Actions;

use App\Containers\Core\User\Models\User;
use App\Containers\Core\User\Tasks\UpdateUserTask;
use App\Containers\Core\User\UI\API\Requests\UpdateUserRequest;
use App\Ship\Parents\Actions\Action;

class UpdateUserAction extends Action
{
    public function run(UpdateUserRequest $request): User
    {
        $sanitizedData = $request->sanitizeInput([
            'password',
            'name',
            'gender',
            'birth',
            'social_token',
            'social_expires_in',
            'social_refresh_token',
            'social_token_secret'
        ]);

        return app(UpdateUserTask::class)->run($sanitizedData, $request->id);
    }
}
