<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Page\Http\Controllers\Api\PostApiController;

Route::get('posts', [PostApiController::class, 'index'])
    ->name('posts.index');
