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
    ){}

    
    public static function fromModel(User $model): self
    {
        return new self(
            create_pages: $model->can('create_pages'),
            update_pages: $model->can('update_pages'),
            create_posts: $model->can('create_posts'),
            update_posts: $model->can('update_posts'),
            create_users: $model->can('create_users'),
            update_users: $model->can('update_users'),
            create_categories: $model->can('create_categories'),
            update_categories: $model->can('update_categories'),
            create_layouts: $model->can('create_layouts'),
            update_layouts: $model->can('update_layouts'),
            create_folders: $model->can('create_folders'),
            update_folders: $model->can('update_folders'),
            create_menus: $model->can('create_menus'),
            update_menus: $model->can('update_menus'),
            create_roles: $model->can('create_roles'),
            update_roles: $model->can('update_roles'),
            create_testimonials: $model->can('create_testimonials'),
            update_testimonials: $model->can('update_testimonials'),
        );
    }
    
}
