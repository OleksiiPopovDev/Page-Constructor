<?php

/**
 * @apiGroup           RolePermission
 * @apiName            createRole
 * @api                {post} /v1/roles Create a Role
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated User
 *
 * @apiParam           {String} name Unique Role Name
 * @apiParam           {String} [description]
 * @apiParam           {String} [display_name]
 *
 * @apiUse             RoleSuccessSingleResponse
 */

use App\Containers\Core\Authorization\UI\API\Controllers\Controller;
use Illuminate\Support\Facades\Route;

Route::post('roles', [Controller::class, 'createRole'])
    ->name('api_authorization_create_role')
    ->middleware(['auth:api']);
