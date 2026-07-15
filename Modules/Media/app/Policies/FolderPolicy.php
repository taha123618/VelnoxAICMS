<?php

namespace Modules\Media\Policies;

use Modules\Auth\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Media\Models\Folder;

class FolderPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        return $user->can('create_folders');
    }

    public function update(User $user, Folder $model)
    {
        return $user->can('edit_folders');
    }

    public function delete(User $user, Folder $model): bool
    {
        return $user->can('delete_folders')
            && $model->children()->doesntExist()
            && $model->media()->doesntExist();
    }
}
