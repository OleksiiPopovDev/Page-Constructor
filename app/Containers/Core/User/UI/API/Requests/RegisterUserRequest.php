<?php

namespace App\Containers\Core\User\UI\API\Requests;

use App\Ship\Parents\Requests\Request;
use PopovAleksey\Mapper\Mapper;

class RegisterUserRequest extends Request
{
    /**
     * Define which Roles and/or Permissions has access to this request.
     */
    protected array $access = [
        'permissions' => '',
        'roles' => '',
    ];

    /**
     * Id's that needs decoding before applying the validation rules.
     */
    protected array $decode = [

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
            'email' => 'required|email|max:40|unique:users,email',
            'password' => 'required|min:6|max:30',
            'name' => 'required|min:2|max:50',
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
