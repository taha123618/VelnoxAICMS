<?php

namespace Modules\Category\Policies;

use Modules\Auth\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Category\Models\Category;

class CategoryPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        return $user->can('create_categories');
    }

    public function update(User $user, Category $category)
    {
        return $user->can('edit_categories');
    }

    public function delete(User $user, Category $category): bool
    {
        return $user->can('delete_categories')
            && $category->children()->doesntExist()
            && $category->posts()->doesntExist();
    }
}
