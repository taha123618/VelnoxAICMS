<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Contacts\Http\Controllers\ContactsController;

Route::post('contacts', [ContactsController::class, 'store'])
    ->name('contacts.store');

Route::group(['middleware' => ['auth', 'demo.protect', 'verified'], 'as' => 'admin.', 'prefix' => 'cp'], function (): void {
    Route::resource('contacts', ContactsController::class)
        ->only(['index', 'show', 'destroy'])
        ->names('contacts');
});
