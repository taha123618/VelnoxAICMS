<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Marketplace\Http\Controllers\MarketplaceController;

Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'cp', 'as' => 'marketplace.'], function (): void {
    Route::get('marketplace', [MarketplaceController::class, 'index'])->name('index');
    Route::post('marketplace/{module}/toggle', [MarketplaceController::class, 'toggle'])->name('toggle');
    Route::post('marketplace/install', [MarketplaceController::class, 'install'])->name('install');
    Route::delete('marketplace/{name}', [MarketplaceController::class, 'destroy'])->name('destroy');
});
