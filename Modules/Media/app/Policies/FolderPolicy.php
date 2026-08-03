<?php

declare(strict_types=1);

namespace Modules\Media\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Auth\Models\User;
use Modules\Media\Models\Folder;

class FolderPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        return $user->can('create_folders');
    }

    public function update(User $user, Folder $folder)
    {
        return $user->can('edit_folders');
    }

    public function delete(User $user, Folder $folder): bool
    {
        return $user->can('delete_folders')
            && $folder->children()->doesntExist()
            && $folder->media()->doesntExist();
    }
}
