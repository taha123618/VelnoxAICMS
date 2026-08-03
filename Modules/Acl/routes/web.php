<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Acl\Http\Controllers\AclController;

Route::middleware(['web', 'auth'])->prefix('admin/acl')->name('admin.acl.')->group(function (): void {
    Route::get('/', [AclController::class, 'index'])->name('index');
    Route::post('/roles', [AclController::class, 'storeRole'])->name('roles.store');
    Route::put('/roles/{id}', [AclController::class, 'updateRole'])->name('roles.update');
    Route::delete('/roles/{id}', [AclController::class, 'destroyRole'])->name('roles.destroy');
});
