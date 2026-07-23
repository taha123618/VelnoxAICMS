<?php

use Illuminate\Support\Facades\Route;
use Modules\Localization\Http\Controllers\LanguageController;

Route::middleware(['web', 'auth', 'verified'])->prefix('admin')->group(function () {
    Route::resource('languages', LanguageController::class)->names('admin.languages');
});