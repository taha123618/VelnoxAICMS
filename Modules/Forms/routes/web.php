<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Forms\Http\Controllers\FormsController;
use Modules\Forms\Http\Controllers\FormSubmissionController;

// Public form submission route
Route::post('f/{slug}', [FormSubmissionController::class, 'store'])->name('forms.submit');

Route::middleware(['web', 'auth'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::resource('forms', FormsController::class);
});
