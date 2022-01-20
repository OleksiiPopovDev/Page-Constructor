<?php

namespace App\Containers\Core\User\Models;

use App\Containers\Core\Authentication\Traits\AuthenticationTrait;
use App\Containers\Core\Authorization\Traits\AuthorizationTrait;
use App\Ship\Parents\Models\UserModel;
use Illuminate\Notifications\Notifiable;

class User extends UserModel implements UserInterface
{
    use AuthorizationTrait;
    use AuthenticationTrait;
    use Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'device',
        'platform',
        'gender',
        'birth',
        'social_provider',
        'social_token',
        'social_refresh_token',
        'social_expires_in',
        'social_token_secret',
        'social_id',
        'social_avatar',
        'social_avatar_original',
        'social_nickname',
        'email_verified_at',
        'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_admin' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    protected $dates      = [
        'birth',
        'email_verified_at',
        'created_at',
        'updated_at',
    ];
}
