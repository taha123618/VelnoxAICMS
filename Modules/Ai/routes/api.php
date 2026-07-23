<?php

use Illuminate\Support\Facades\Route;

use Modules\Ai\Http\Controllers\AiController;
use Modules\Ai\Http\Controllers\AiJobController;

Route::middleware(['auth:sanctum'])->prefix('ai')->group(function () {
    Route::post('/generate-section', [AiController::class, 'generateSection']);
    Route::post('/generate-content', [AiController::class, 'generateContent']);
    Route::post('/optimize-seo', [AiController::class, 'optimizeSeo']);

    Route::post('/jobs', [AiJobController::class, 'store']);
    Route::get('/jobs/{id}', [AiJobController::class, 'show']);
    Route::post('/jobs/{id}/retry', [AiJobController::class, 'retry']);
    Route::post('/jobs/{id}/cancel', [AiJobController::class, 'cancel']);
});





