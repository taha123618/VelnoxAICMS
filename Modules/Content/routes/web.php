<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Content\Http\Controllers\CollectionController;
use Modules\Content\Http\Controllers\EntryController;
use Modules\Content\Http\Controllers\FieldController;

Route::group(['middleware' => ['auth', 'verified'], 'as' => 'admin.', 'prefix' => 'cp'], function (): void {
    Route::resource('collections', CollectionController::class);
    Route::resource('collections.fields', FieldController::class);
    Route::resource('collections.entries', EntryController::class);
});
