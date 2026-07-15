<?php

use Illuminate\Support\Facades\Route;
use Modules\Category\Http\Controllers\CategoryController;

Route::group(['middleware' => ['auth', 'demo.protect', 'verified'], 'as' => 'admin.', 'prefix' => 'cp'], function () {
    Route::resource('categories', CategoryController::class)
        ->names('categories')
        ->except('show');
});
