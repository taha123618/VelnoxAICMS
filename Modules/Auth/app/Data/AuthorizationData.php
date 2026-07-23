<?php

namespace Modules\Auth\Data;

use Modules\Auth\Models\User;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript()]
class AuthorizationData extends Data
{
    public function __construct(
        public bool $create_pages,
        public bool $update_pages,
        public bool $create_posts,
        public bool $update_posts,
        public bool $create_users,
        public bool $update_users,
        public bool $create_categories,
        public bool $update_categories,
        public bool $create_layouts,
        public bool $update_layouts,
        public bool $create_folders,
        public bool $update_folders,
        public bool $create_menus,
        public bool $update_menus,
        public bool $create_roles,
        public bool $update_roles,
        public bool $create_testimonials,
        public bool $update_testimonials
    ) {}

    public static function fromModel(User $user): self
    {
        return new self(
            create_pages: $user->can('create_pages'),
            update_pages: $user->can('update_pages'),
            create_posts: $user->can('create_posts'),
            update_posts: $user->can('update_posts'),
            create_users: $user->can('create_users'),
            update_users: $user->can('update_users'),
            create_categories: $user->can('create_categories'),
            update_categories: $user->can('update_categories'),
            create_layouts: $user->can('create_layouts'),
            update_layouts: $user->can('update_layouts'),
            create_folders: $user->can('create_folders'),
            update_folders: $user->can('update_folders'),
            create_menus: $user->can('create_menus'),
            update_menus: $user->can('update_menus'),
            create_roles: $user->can('create_roles'),
            update_roles: $user->can('update_roles'),
            create_testimonials: $user->can('create_testimonials'),
            update_testimonials: $user->can('update_testimonials'),
        );
    }
}
