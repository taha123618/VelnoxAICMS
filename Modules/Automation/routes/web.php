<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Automation\Http\Controllers\WebhookController;

Route::middleware(['web', 'auth', 'verified'])->prefix('admin')->group(function (): void {
    Route::resource('webhooks', WebhookController::class)->names('admin.webhooks');
});
