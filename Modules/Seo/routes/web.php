<?php

use Illuminate\Support\Facades\Route;
use Modules\Seo\Http\Controllers\SeoController;

Route::middleware(['web', 'auth'])->group(function () {
    Route::post('seo', [SeoController::class, 'store'])->name('admin.seo.store');
});