<?php

use Illuminate\Support\Facades\Route;
use Modules\Layout\Http\Controllers\LayoutController;
use Modules\Layout\Http\Controllers\LayoutMetadataController;

Route::group(['middleware' => ['auth', 'verified', 'demo.protect'], 'as' => 'admin.', 'prefix' => 'cp'], function () {
    Route::resource('layouts', LayoutController::class)
        ->names('layouts')
        ->except('show');

    Route::get('layouts/{layout}/meta', [LayoutMetadataController::class, 'edit'])
        ->name('layouts.meta.edit');

    Route::put('layouts/{layout}/meta', [LayoutMetadataController::class, 'update'])
        ->name('layouts.meta.update');
});
