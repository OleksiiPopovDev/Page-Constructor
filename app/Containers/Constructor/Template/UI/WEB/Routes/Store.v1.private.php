<?php

use App\Containers\Constructor\Template\UI\WEB\Controllers\ControllerTemplate;
use App\Containers\Constructor\Template\UI\WEB\Controllers\ControllerTheme;
use Illuminate\Support\Facades\Route;

Route::post(config('apiato.link.constructor') . '/theme/store', [ControllerTheme::class, 'store'])
    ->name('constructor_theme_store')
    ->middleware(['auth:web']);

Route::post(config('apiato.link.constructor') . '/template/store', [ControllerTemplate::class, 'store'])
    ->name('constructor_template_store')
    ->middleware(['auth:web']);

