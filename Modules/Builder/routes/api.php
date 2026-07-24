<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Builder\Http\Controllers\Api\AiSectionGeneratorController;
use Modules\Builder\Http\Controllers\Api\BuilderController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function (): void {
    Route::get('builder/pages/{page}', [BuilderController::class, 'show'])->name('builder.pages.show');
    Route::put('builder/pages/{page}', [BuilderController::class, 'update'])->name('builder.pages.update');
});

Route::middleware(['auth:sanctum'])->prefix('builder/ai')->group(function (): void {
    Route::post('/generate-section', AiSectionGeneratorController::class);
});
