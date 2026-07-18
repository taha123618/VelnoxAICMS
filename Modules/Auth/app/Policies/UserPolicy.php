<?php

namespace Modules\Auth\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Auth\Models\User;

class UserPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        return $user->can('create_users');
    }

    public function update(User $user, User $model)
    {
        return $user->can('edit_users') && $user->isNot($model);
    }

    public function delete(User $user, User $model): bool
    {
        return $user->can('delete_users')
            && $model->getMainRole()?->name !== 'administrator'
            && $user->isNot($model);
    }
}
