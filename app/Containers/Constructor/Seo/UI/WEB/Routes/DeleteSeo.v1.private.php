<?php

use App\Containers\Constructor\Seo\UI\WEB\Controllers\Controller;
use Illuminate\Support\Facades\Route;

Route::delete('constructor/seo/{id}', [Controller::class, 'destroy'])
    ->name('constructor_seo_destroy')
    ->middleware(['auth:web']);

