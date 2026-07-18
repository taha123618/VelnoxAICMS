<?php

use Illuminate\Support\Facades\Route;
use Modules\Marketplace\Http\Controllers\MarketplaceController;

Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'cp', 'as' => 'marketplace.'], function () {
    Route::get('marketplace', [MarketplaceController::class, 'index'])->name('index');
    Route::post('marketplace/{module}/toggle', [MarketplaceController::class, 'toggle'])->name('toggle');
    Route::post('marketplace/install', [MarketplaceController::class, 'install'])->name('install');
});
