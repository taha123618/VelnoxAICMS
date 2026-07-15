<?php

namespace Modules\Testimonial\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Modules\Testimonial\Models\Testimonial;

class TestimonialStatusController extends Controller
{
    public function __invoke(Testimonial $testimonial)
    {
        Gate::authorize('update', $testimonial);
        
        $testimonial->togglePublish();

        return Redirect::back()->with('success', 'Status changed!');
    }
}
