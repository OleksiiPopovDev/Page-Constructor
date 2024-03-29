<?php

namespace App\Containers\Core\User\UI\API\Controllers;

use App\Containers\Core\User\Actions\CreateAdminAction;
use App\Containers\Core\User\Actions\DeleteUserAction;
use App\Containers\Core\User\Actions\FindUserByIdAction;
use App\Containers\Core\User\Actions\ForgotPasswordAction;
use App\Containers\Core\User\Actions\GetAllAdminsAction;
use App\Containers\Core\User\Actions\GetAllClientsAction;
use App\Containers\Core\User\Actions\GetAllUsersAction;
use App\Containers\Core\User\Actions\GetAuthenticatedUserAction;
use App\Containers\Core\User\Actions\RegisterUserAction;
use App\Containers\Core\User\Actions\ResetPasswordAction;
use App\Containers\Core\User\Actions\UpdateUserAction;
use App\Containers\Core\User\UI\API\Requests\CreateAdminRequest;
use App\Containers\Core\User\UI\API\Requests\DeleteUserRequest;
use App\Containers\Core\User\UI\API\Requests\FindUserByIdRequest;
use App\Containers\Core\User\UI\API\Requests\ForgotPasswordRequest;
use App\Containers\Core\User\UI\API\Requests\GetAllUsersRequest;
use App\Containers\Core\User\UI\API\Requests\GetAuthenticatedUserRequest;
use App\Containers\Core\User\UI\API\Requests\RegisterUserRequest;
use App\Containers\Core\User\UI\API\Requests\ResetPasswordRequest;
use App\Containers\Core\User\UI\API\Requests\UpdateUserRequest;
use App\Containers\Core\User\UI\API\Transformers\UserPrivateProfileTransformer;
use App\Containers\Core\User\UI\API\Transformers\UserTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class Controller extends ApiController
{
    public function registerUser(RegisterUserRequest $request): array
    {
        $user = app(RegisterUserAction::class)->run($request);
        return $this->transform($user, UserTransformer::class);
    }

    public function createAdmin(CreateAdminRequest $request): array
    {
        $admin = app(CreateAdminAction::class)->run($request);
        return $this->transform($admin, UserTransformer::class);
    }

    public function updateUser(UpdateUserRequest $request): array
    {
        $user = app(UpdateUserAction::class)->run($request);
        return $this->transform($user, UserTransformer::class);
    }

    public function deleteUser(DeleteUserRequest $request): JsonResponse
    {
        app(DeleteUserAction::class)->run($request);
        return $this->noContent();
    }

    public function getAllUsers(GetAllUsersRequest $request): array
    {
        $users = app(GetAllUsersAction::class)->run();
        return $this->transform($users, UserTransformer::class);
    }

    public function getAllClients(GetAllUsersRequest $request): array
    {
        $users = app(GetAllClientsAction::class)->run();
        return $this->transform($users, UserTransformer::class);
    }

    public function getAllAdmins(GetAllUsersRequest $request): array
    {
        $users = app(GetAllAdminsAction::class)->run();
        return $this->transform($users, UserTransformer::class);
    }

    public function findUserById(FindUserByIdRequest $request): array
    {
        $user = app(FindUserByIdAction::class)->run($request);
        return $this->transform($user, UserTransformer::class);
    }

    public function getAuthenticatedUser(GetAuthenticatedUserRequest $request): array
    {
        $user = app(GetAuthenticatedUserAction::class)->run();
        return $this->transform($user, UserPrivateProfileTransformer::class);
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        app(ResetPasswordAction::class)->run($request);
        return $this->noContent(204);
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        app(ForgotPasswordAction::class)->run($request);
        return $this->noContent(202);
    }
}
