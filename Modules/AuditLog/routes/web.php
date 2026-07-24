<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\AuditLog\Http\Controllers\AuditLogController;

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
    Route::get('audit-log', [AuditLogController::class, 'index'])->name('audit-log.index');
});
