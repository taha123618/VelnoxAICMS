<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Marketplace\Http\Controllers\Api\AiMarketplaceController;

Route::middleware(['auth:sanctum'])->prefix('marketplace')->group(function (): void {
    Route::post('/generate-listing', AiMarketplaceController::class);
});
