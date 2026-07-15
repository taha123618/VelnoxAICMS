<?php

namespace Modules\Testimonial\Actions;

use Illuminate\Http\Request;
use Modules\Testimonial\Models\Testimonial;

class CreateTestimonialAction
{
    public function handle(Request $request)
    {

        return Testimonial::create([
            'name' => $request->name,
            'avatar' => $request->avatar,
            'title' => $request->title,
            'comment' => $request->comment
        ]);
    }
}
