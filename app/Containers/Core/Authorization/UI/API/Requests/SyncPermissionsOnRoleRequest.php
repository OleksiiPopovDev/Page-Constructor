<?php

namespace App\Containers\Core\Authorization\UI\API\Requests;

use App\Ship\Parents\Requests\Request;
use PopovAleksey\Mapper\Mapper;

class SyncPermissionsOnRoleRequest extends Request
{
    /**
     * Define which Roles and/or Permissions has access to this request.
     */
    protected array $access = [
        'roles' => '',
        'permissions' => 'manage-roles',
    ];

    /**
     * Id's that needs decoding before applying the validation rules.
     */
    protected array $decode = [
        'permissions_ids.*',
        'role_id',
    ];

    /**
     * Defining the URL parameters (`/stores/999/items`) allows applying
     * validation rules on them and allows accessing them like request data.
     */
    protected array $urlParameters = [

    ];

    public function rules(): array
    {
        return [
            'permissions_ids' => 'required',
            'permissions_ids.*' => 'exists:' . config('permission.table_names.permissions') . ',id',
            'role_id' => 'required|exists:' . config('permission.table_names.roles') . ',id',
        ];
    }

    public function authorize(): bool
    {
        return $this->check([
            'hasAccess',
        ]);
    }

    public function mapped(): Mapper
    {
        return new Mapper();
    }
}
