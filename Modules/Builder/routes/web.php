<?php

use Illuminate\Support\Facades\Route;
use Modules\Builder\Http\Controllers\LinkPickerController;

Route::group(['middleware' => ['auth', 'verified'], 'as' => 'admin.', 'prefix' => 'cp'], function () {
    Route::get('links', LinkPickerController::class)->name('links.picker');
});
