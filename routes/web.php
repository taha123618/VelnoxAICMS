<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PreviewPageController;


Route::get('/', [PageController::class, 'index'])->name('home');

Route::get('/preview/{page}', PreviewPageController::class)
    ->middleware(['auth'])
    ->name('pages.preview');

Route::get('/{page:slug}', [PageController::class, 'show'])
    ->name('pages.show');
