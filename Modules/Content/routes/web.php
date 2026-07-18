<?php

use Illuminate\Support\Facades\Route;
use Modules\Content\Http\Controllers\CollectionController;
use Modules\Content\Http\Controllers\FieldController;
use Modules\Content\Http\Controllers\EntryController;

Route::group(['middleware' => ['auth', 'verified'], 'as' => 'admin.', 'prefix' => 'cp'], function () {
    Route::resource('collections', CollectionController::class);
    Route::resource('collections.fields', FieldController::class);
    Route::resource('collections.entries', EntryController::class);
});
