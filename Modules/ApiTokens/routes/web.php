<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\ApiTokens\Http\Controllers\ApiTokensController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth', 'verified'])->prefix('admin')->group(function (): void {
    Route::get('api-tokens', [ApiTokensController::class, 'index'])->name('api-tokens.index');
    Route::post('api-tokens', [ApiTokensController::class, 'store'])->name('api-tokens.store');
    Route::delete('api-tokens/{id}', [ApiTokensController::class, 'destroy'])->name('api-tokens.destroy');
});
