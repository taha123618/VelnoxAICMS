<?php

declare(strict_types=1);

namespace Modules\Testimonial\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Modules\Testimonial\Models\Testimonial;

class TestimonialStatusController extends Controller
{
    public function __invoke(Testimonial $testimonial)
    {
        Gate::authorize('update', $testimonial);

        $testimonial->togglePublish();

        return back()->with('success', 'Status changed!');
    }
}
