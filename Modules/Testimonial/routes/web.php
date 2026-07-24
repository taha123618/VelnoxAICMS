<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Testimonial\Http\Controllers\TestimonialController;
use Modules\Testimonial\Http\Controllers\TestimonialStatusController;

Route::group(['middleware' => ['auth', 'demo.protect', 'verified'], 'as' => 'admin.', 'prefix' => 'cp'], function (): void {
    Route::put('testimonials/{testimonial}/status', TestimonialStatusController::class)->name('testimonials.status');

    Route::resource('testimonials', TestimonialController::class)
        ->except('show')
        ->names('testimonials');
});
