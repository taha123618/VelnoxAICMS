<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Seo\Http\Controllers\Api\AiSeoController;

Route::middleware(['auth:sanctum'])->prefix('seo')->group(function (): void {
    Route::post('/generate-metadata', AiSeoController::class);
});
