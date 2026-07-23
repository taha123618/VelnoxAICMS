<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Category\Http\Controllers\Api\CategoryApiController;

Route::get('categories', [CategoryApiController::class, 'index'])
    ->name('categories.index');
