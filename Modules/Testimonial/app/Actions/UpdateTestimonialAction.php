<?php

namespace Modules\Testimonial\Actions;

use Modules\Testimonial\Http\Requests\UpdateTestimonialRequest;
use Modules\Testimonial\Models\Testimonial;

class UpdateTestimonialAction
{
    public function handle(UpdateTestimonialRequest $updateTestimonialRequest, Testimonial $testimonial)
    {
        return tap($testimonial)->update([
            'name' => $updateTestimonialRequest->name,
            'avatar' => $updateTestimonialRequest->avatar,
            'title' => $updateTestimonialRequest->title,
            'comment' => $updateTestimonialRequest->comment,
        ]);
    }
}
