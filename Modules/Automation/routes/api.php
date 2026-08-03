<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Automation\Http\Controllers\Api\AiAutomationController;

Route::middleware(['auth:sanctum'])->prefix('automation')->group(function (): void {
    Route::post('/generate-rule', AiAutomationController::class);
});
