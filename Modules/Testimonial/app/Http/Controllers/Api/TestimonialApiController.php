<?php

namespace Modules\Testimonial\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
