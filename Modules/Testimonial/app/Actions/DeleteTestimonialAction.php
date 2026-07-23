<?php

declare(strict_types=1);

namespace Modules\Testimonial\Actions;

use Modules\Testimonial\Models\Testimonial;

class DeleteTestimonialAction
{
    public function handle(Testimonial $testimonial): void
    {

        $testimonial->delete();

    }
}
