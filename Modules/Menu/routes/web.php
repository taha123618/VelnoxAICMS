<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Menu\Http\Controllers\MenuController;

Route::group(['middleware' => ['auth', 'demo.protect', 'verified'], 'as' => 'admin.', 'prefix' => 'cp'], function (): void {
    Route::resource('menus', MenuController::class)->names('menus');
});
