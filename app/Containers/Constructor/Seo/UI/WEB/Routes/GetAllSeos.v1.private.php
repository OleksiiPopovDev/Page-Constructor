<?php

use App\Containers\Constructor\Seo\UI\WEB\Controllers\Controller;
use Illuminate\Support\Facades\Route;

Route::get('seo', [Controller::class, 'index'])
    ->name('web_seo_index')
    ->middleware(['auth:web']);

