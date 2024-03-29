<?php

use App\Containers\Constructor\Seo\UI\WEB\Controllers\Controller;
use Illuminate\Support\Facades\Route;

Route::post(config('apiato.link.constructor') . '/seo/store', [Controller::class, 'store'])
    ->name('constructor_seo_store')
    ->middleware(['auth:web']);

