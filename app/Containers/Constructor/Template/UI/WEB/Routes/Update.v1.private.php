<?php

use App\Containers\Constructor\Template\UI\WEB\Controllers\ControllerTemplate;
use App\Containers\Constructor\Template\UI\WEB\Controllers\ControllerTheme;
use Illuminate\Support\Facades\Route;

Route::patch(config('apiato.link.constructor') . '/templates/{id}', [ControllerTemplate::class, 'update'])
    ->name('constructor_template_update')
    ->middleware(['auth:web']);

Route::patch(config('apiato.link.constructor') . '/template/name/{id}', [ControllerTemplate::class, 'updateName'])
    ->name('constructor_template_name_update')
    ->middleware(['auth:web']);

Route::patch(config('apiato.link.constructor') . '/theme/activate/{id}', [ControllerTheme::class, 'activate'])
    ->name('constructor_theme_activate')
    ->middleware(['auth:web']);

Route::patch(config('apiato.link.constructor') . '/theme/name/{id}', [ControllerTheme::class, 'updateName'])
    ->name('constructor_theme_name_update')
    ->middleware(['auth:web']);