<?php

namespace Modules\Menu\Policies;

use Modules\Auth\Models\User;
use Modules\Menu\Models\Menu;
use Illuminate\Auth\Access\HandlesAuthorization;

class MenuPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        return $user->can('create_menus');
    }

    public function update(User $user, Menu $menu)
    {
        return $user->can('edit_menus');
    }

    public function delete(User $user, Menu $menu): bool
    {
        return $user->can('delete_menus');
    }


}
