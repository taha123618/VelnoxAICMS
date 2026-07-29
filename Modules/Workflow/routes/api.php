<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Workflow\Http\Controllers\Api\AiWorkflowController;

Route::middleware(['auth:sanctum'])->prefix('workflow')->group(function (): void {
    Route::post('/generate-workflow', AiWorkflowController::class);
});
