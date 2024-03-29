<?php

use App\Containers\Dashboard\Content\UI\WEB\Controllers\Controller;
use Illuminate\Support\Facades\Route;

Route::patch(config('apiato.link.dashboard') . '/content/{id}', [Controller::class, 'update'])
    ->name('dashboard_content_update')
    ->middleware(['auth:web']);

