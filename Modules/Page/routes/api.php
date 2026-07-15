<?php

use Illuminate\Support\Facades\Route;
use Modules\Page\Http\Controllers\Api\PostApiController;

Route::get('posts', [PostApiController::class, 'index'])
    ->name('posts.index');
