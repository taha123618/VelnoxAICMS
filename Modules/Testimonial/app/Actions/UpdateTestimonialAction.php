<?php

namespace Modules\Testimonial\Actions;

use Modules\Testimonial\Http\Requests\UpdateTestimonialRequest;
use Modules\Testimonial\Models\Testimonial;

class UpdateTestimonialAction
{
    public function handle(UpdateTestimonialRequest $request, Testimonial $testimonial)
    {
        return tap($testimonial)->update([
            'name' => $request->name,
            'avatar' => $request->avatar,
            'title' => $request->title,
            'comment' => $request->comment,
        ]);
    }
}
