<?php

use Illuminate\Support\Facades\Route;
use Modules\Testimonial\Http\Controllers\Api\TestimonialApiController;

Route::get('testimonials', [TestimonialApiController::class, 'index'])
    ->name('testimonials.index');
