<?php

use Illuminate\Support\Facades\Route;
use Modules\Media\Http\Controllers\DraggableMediaController;
use Modules\Media\Http\Controllers\FolderController;
use Modules\Media\Http\Controllers\MediaController;

Route::group(['middleware' => ['auth', 'demo.protect', 'verified'], 'as' => 'admin.', 'prefix' => 'cp'], function (): void {
    Route::post('files/{folder}', [MediaController::class, 'store'])
        ->name('files.store')
        ->withoutMiddleware(['throttle']);

    Route::resource('files', MediaController::class)
        ->only('show', 'destroy')
        ->names('files');

    Route::put('draggable-media', [DraggableMediaController::class, 'update'])
        ->name('media.drag');

    Route::put('draggable-media/{folder}', [DraggableMediaController::class, 'drag'])
        ->name('media.folder.drag');

    Route::resource('media', FolderController::class)
        ->except('show')
        ->parameters([
            'media' => 'folder',
        ])
        ->names('folders');
});
