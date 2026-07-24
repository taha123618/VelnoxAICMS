<?php

namespace Modules\Testimonial\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Auth\Models\User;
use Modules\Testimonial\Models\Testimonial;

class TestimonialPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        return $user->can('create_testimonials');
    }

    public function update(User $user, Testimonial $testimonial)
    {
        return $user->can('edit_testimonials');
    }

    public function delete(User $user, Testimonial $testimonial): bool
    {
        return $user->can('delete_testimonials');
    }
}
