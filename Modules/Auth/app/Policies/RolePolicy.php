<?php

namespace Modules\Auth\Policies;

use Modules\Auth\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Auth\Models\Role;

class RolePolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        return $user->can('create_roles');
    }

    public function update(User $user, Role $role)
    {
        return $user->can('edit_roles');
    }

    public function delete(User $user, Role $role): bool
    {
        return $user->can('delete_roles')
            && $role->users()->doesntExist()
            && $role->permissions()->doesntExist();
    }
}
