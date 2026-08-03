<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Testimonial\Http\Controllers\Api\AiTestimonialController;
use Modules\Testimonial\Http\Controllers\Api\TestimonialApiController;

Route::get('testimonials', [TestimonialApiController::class, 'index'])
    ->name('testimonials.index');

Route::middleware(['auth:sanctum'])->prefix('testimonial')->group(function (): void {
    Route::post('/generate-testimonial', AiTestimonialController::class);
});
