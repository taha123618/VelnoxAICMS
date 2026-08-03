<?php

namespace Modules\Page\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Auth\Models\User;
use Modules\Page\Models\Page;

class PagePolicy
{
    use HandlesAuthorization;

    public function create_post(User $user)
    {
        return $user->can('create_posts');
    }

    public function update_post(User $user, Page $page)
    {
        return $user->can('edit_posts');
    }

    public function delete_post(User $user, Page $page): bool
    {
        return $user->can('delete_posts')
            && $page->menu_items()->doesntExist();
    }

    public function create_page(User $user)
    {
        return $user->can('create_pages');
    }

    public function update_page(User $user, Page $page)
    {
        return $user->can('edit_pages');
    }

    public function delete_page(User $user, Page $page): bool
    {
        return $user->can('delete_pages')
            && $page->menu_items()->doesntExist();
    }
}
