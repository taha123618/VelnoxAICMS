<?php

use Illuminate\Support\Facades\Route;
use Modules\Dashboard\Http\Controllers\DashboardController;

Route::group(['middleware' => ['auth', 'verified'], 'as' => 'admin.', 'prefix' => 'cp'], function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');
});
