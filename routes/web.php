<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\PreviewPageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'index'])->name('home');

Route::get('/preview/{page}', PreviewPageController::class)
    ->middleware(['auth'])
    ->name('pages.preview');

Route::get('/{page:slug}', [PageController::class, 'show'])
    ->name('pages.show');
