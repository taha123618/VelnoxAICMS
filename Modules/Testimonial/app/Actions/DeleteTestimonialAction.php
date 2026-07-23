<?php

namespace Modules\Testimonial\Actions;

use Modules\Testimonial\Models\Testimonial;

class DeleteTestimonialAction
{
    public function handle(Testimonial $testimonial)
    {

        $testimonial->delete();

    }
}
