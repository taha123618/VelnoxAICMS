<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Forms\Http\Controllers\Api\AiFormController;

Route::middleware(['auth:sanctum'])->prefix('forms')->group(function (): void {
    Route::post('/generate-form', AiFormController::class);
});
