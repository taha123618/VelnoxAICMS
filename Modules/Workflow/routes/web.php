<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Workflow\Http\Controllers\WorkflowController;

Route::middleware(['web', 'auth', 'verified'])->prefix('admin')->group(function (): void {
    Route::resource('workflows', WorkflowController::class)->names('admin.workflows');
});
