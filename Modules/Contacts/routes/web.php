<?php

use Illuminate\Support\Facades\Route;
use Modules\Contacts\Http\Controllers\ContactsController;

Route::post('contacts', [ContactsController::class, 'store'])
    ->name('contacts.store');

Route::group(['middleware' => ['auth', 'demo.protect', 'verified'], 'as' => 'admin.', 'prefix' => 'cp'], function () {
    Route::resource('contacts', ContactsController::class)
        ->only(['index', 'show', 'destroy'])
        ->names('contacts');
});
