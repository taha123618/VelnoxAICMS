<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Category\Http\Controllers\Api\AiCategoryController;
use Modules\Category\Http\Controllers\Api\CategoryApiController;

Route::get('categories', [CategoryApiController::class, 'index'])
    ->name('categories.index');

Route::middleware(['auth:sanctum'])->prefix('category')->group(function (): void {
    Route::post('/generate-taxonomy', AiCategoryController::class);
});
