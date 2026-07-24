<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Content\Http\Controllers\Api\CollectionController;
use Modules\Content\Http\Controllers\Api\EntryController;

Route::prefix('v1/content')->group(function (): void {
    Route::get('collections', [CollectionController::class, 'index']);
    Route::get('collections/{slug}', [CollectionController::class, 'show']);

    Route::get('collections/{slug}/entries', [EntryController::class, 'index']);
    Route::get('collections/{slug}/entries/{id}', [EntryController::class, 'show']);
});
