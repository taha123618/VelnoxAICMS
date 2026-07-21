<?php

use Illuminate\Support\Facades\Route;
use Modules\Builder\Http\Controllers\Api\BuilderController;
use Modules\Builder\Http\Controllers\Api\AiSectionGeneratorController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::get('builder/pages/{page}', [BuilderController::class, 'show'])->name('builder.pages.show');
    Route::put('builder/pages/{page}', [BuilderController::class, 'update'])->name('builder.pages.update');
});



Route::middleware(['auth:sanctum'])->prefix('builder/ai')->group(function () {
    Route::post('/generate-section', AiSectionGeneratorController::class);
});