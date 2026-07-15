<?php

use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Support\Facades\Route;
use Modules\Page\Http\Controllers\FrontpageController;
use Modules\Page\Http\Controllers\PageController;
use Modules\Page\Http\Controllers\PostController;
use Modules\Page\Http\Controllers\PageMetaController;
use Modules\Page\Http\Controllers\PostMetaController;
use Modules\Page\Http\Controllers\PageContentController;
use Modules\Page\Http\Controllers\PageDuplicationController;
use Modules\Page\Http\Controllers\PageStatusController;
use Modules\Page\Http\Controllers\PostContentController;

Route::group(['middleware' => ['auth', 'demo.protect', 'verified'], 'as' => 'admin.', 'prefix' => 'cp'], function () {
    Route::resource('pages', PageController::class)
        ->except('update')
        ->names('pages');

    Route::put('pages/{page}/content', PageContentController::class)
        ->name('pages.content');

    Route::put('pages/{page}/frontpage', FrontpageController::class)
        ->name('pages.frontpage');

    Route::put('pages/{page}/status', [PageStatusController::class, 'publish'])
        ->name('pages.status');

    Route::put('pages/{page}/save', [PageStatusController::class, 'saveAndpublish'])
        ->name('pages.publish');

    Route::get('pages/{page}/meta', [PageMetaController::class, 'edit'])
        ->name('pages.meta.edit');

    Route::put('pages/{page}/meta', [PageMetaController::class, 'update'])
        ->middleware([HandlePrecognitiveRequests::class])
        ->name('pages.meta.update');

    Route::resource('posts', PostController::class)
        ->except('update')
        ->names('posts');

    Route::put('posts/{post}/content', PostContentController::class)
        ->name('posts.content');

    Route::get('posts/{post}/meta', [PostMetaController::class, 'edit'])
        ->name('posts.meta.edit');

    Route::put('posts/{post}/meta', [PostMetaController::class, 'update'])
        ->middleware([HandlePrecognitiveRequests::class])
        ->name('posts.meta.update');

    Route::post('pages/{page}/duplicate', PageDuplicationController::class)
        ->name('pages.duplicate');
});
