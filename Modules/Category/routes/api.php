<?php

use Illuminate\Support\Facades\Route;
use Modules\Category\Http\Controllers\Api\CategoryApiController;

Route::get('categories', [CategoryApiController::class, 'index'])
    ->name('categories.index');
