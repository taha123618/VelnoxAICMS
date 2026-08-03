<?php

namespace Modules\Layout\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Auth\Models\User;
use Modules\Layout\Models\Layout;

class LayoutPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        return $user->can('create_layouts');
    }

    public function update(User $user, Layout $layout)
    {
        return $user->can('edit_layouts');
    }

    public function delete(User $user, Layout $layout): bool
    {
        return $user->can('delete_layouts') && $layout->pages()->doesntExist();
    }
}
