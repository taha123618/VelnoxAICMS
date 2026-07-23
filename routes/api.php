<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Ai\Http\Controllers\AiJobController;
use Modules\Builder\Http\Controllers\Api\AiSectionGeneratorController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
