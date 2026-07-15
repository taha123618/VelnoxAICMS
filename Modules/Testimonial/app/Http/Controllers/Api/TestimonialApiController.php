<?php

namespace Modules\Testimonial\Http\Controllers\Api;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Testimonial\Data\TestimonialData;
use Modules\Testimonial\Models\Testimonial;

class TestimonialApiController extends Controller
{
    public function index(Request $request)
    {
        $testimonials = Testimonial::query()->published()->latest()->get();

        return TestimonialData::collect($testimonials);
    }
}
