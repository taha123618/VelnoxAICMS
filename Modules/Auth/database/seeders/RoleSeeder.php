<?php

namespace Modules\Auth\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Auth\Models\Permission;
use Modules\Auth\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Artisan::call('cache:clear');
        resolve(PermissionRegistrar::class)->forgetCachedPermissions();

        DB::table(config('permission.table_names.model_has_permissions'))->truncate();
        DB::table(config('permission.table_names.model_has_roles'))->truncate();
        DB::table(config('permission.table_names.role_has_permissions'))->truncate();
        DB::table(config('permission.table_names.permissions'))->truncate();
        DB::table(config('permission.table_names.roles'))->truncate();

        $roles = [
            ['name' => 'administrator', 'label' => 'Administrator', 'guard_name' => 'web'],
            ['name' => 'editor', 'label' => 'Editor', 'guard_name' => 'web'],
            ['name' => 'author', 'label' => 'Author', 'guard_name' => 'web'],
            ['name' => 'user', 'label' => 'User', 'guard_name' => 'web'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

        $permissions = [
            ['name' => 'access_backend', 'label' => 'Access backend', 'group' => 'Access management', 'guard_name' => 'web'],

            ['name' => 'create_users', 'label' => 'Create', 'group' => 'User Management', 'guard_name' => 'web'],
            ['name' => 'edit_users', 'label' => 'Edit', 'group' => 'User Management', 'guard_name' => 'web'],
            ['name' => 'view_users', 'label' => 'View', 'group' => 'User Management', 'guard_name' => 'web'],
            ['name' => 'delete_users', 'label' => 'Delete', 'group' => 'User Management', 'guard_name' => 'web'],

            ['name' => 'create_roles', 'label' => 'Create', 'group' => 'Role', 'guard_name' => 'web'],
            ['name' => 'edit_roles', 'label' => 'Edit', 'group' => 'Role', 'guard_name' => 'web'],
            ['name' => 'view_roles', 'label' => 'View', 'group' => 'Role', 'guard_name' => 'web'],
            ['name' => 'delete_roles', 'label' => 'Delete', 'group' => 'Role', 'guard_name' => 'web'],

            ['name' => 'create_posts', 'label' => 'Create', 'group' => 'Posts', 'guard_name' => 'web'],
            ['name' => 'edit_posts', 'label' => 'Edit', 'group' => 'Posts', 'guard_name' => 'web'],
            ['name' => 'view_posts', 'label' => 'View', 'group' => 'Posts', 'guard_name' => 'web'],
            ['name' => 'delete_posts', 'label' => 'Delete', 'group' => 'Posts', 'guard_name' => 'web'],

            ['name' => 'create_pages', 'label' => 'Create', 'group' => 'Pages', 'guard_name' => 'web'],
            ['name' => 'edit_pages', 'label' => 'Edit', 'group' => 'Pages', 'guard_name' => 'web'],
            ['name' => 'view_pages', 'label' => 'View', 'group' => 'Pages', 'guard_name' => 'web'],
            ['name' => 'delete_pages', 'label' => 'Delete', 'group' => 'Pages', 'guard_name' => 'web'],

            ['name' => 'create_categories', 'label' => 'Create', 'group' => 'Categories', 'guard_name' => 'web'],
            ['name' => 'edit_categories', 'label' => 'Edit', 'group' => 'Categories', 'guard_name' => 'web'],
            ['name' => 'view_categories', 'label' => 'View', 'group' => 'Categories', 'guard_name' => 'web'],
            ['name' => 'delete_categories', 'label' => 'Delete', 'group' => 'Categories', 'guard_name' => 'web'],

            ['name' => 'create_layouts', 'label' => 'Create', 'group' => 'Layouts', 'guard_name' => 'web'],
            ['name' => 'edit_layouts', 'label' => 'Edit', 'group' => 'Layouts', 'guard_name' => 'web'],
            ['name' => 'view_layouts', 'label' => 'View', 'group' => 'Layouts', 'guard_name' => 'web'],
            ['name' => 'delete_layouts', 'label' => 'Delete', 'group' => 'Layouts', 'guard_name' => 'web'],

            ['name' => 'create_menus', 'label' => 'Create', 'group' => 'Menus', 'guard_name' => 'web'],
            ['name' => 'edit_menus', 'label' => 'Edit', 'group' => 'Menus', 'guard_name' => 'web'],
            ['name' => 'view_menus', 'label' => 'View', 'group' => 'Menus', 'guard_name' => 'web'],
            ['name' => 'delete_menus', 'label' => 'Delete', 'group' => 'Menus', 'guard_name' => 'web'],

            ['name' => 'create_folders', 'label' => 'Create', 'group' => 'Folders', 'guard_name' => 'web'],
            ['name' => 'edit_folders', 'label' => 'Edit', 'group' => 'Folders', 'guard_name' => 'web'],
            ['name' => 'view_folders', 'label' => 'View', 'group' => 'Folders', 'guard_name' => 'web'],
            ['name' => 'delete_folders', 'label' => 'Delete', 'group' => 'Folders', 'guard_name' => 'web'],

            ['name' => 'create_testimonials', 'label' => 'Create', 'group' => 'Testimonials', 'guard_name' => 'web'],
            ['name' => 'edit_testimonials', 'label' => 'Edit', 'group' => 'Testimonials', 'guard_name' => 'web'],
            ['name' => 'view_testimonials', 'label' => 'View', 'group' => 'Testimonials', 'guard_name' => 'web'],
            ['name' => 'delete_testimonials', 'label' => 'Delete', 'group' => 'Testimonials', 'guard_name' => 'web'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        $editorPermissions = [
            'create_posts',
            'edit_posts',
            'view_posts',
            'delete_posts',
            'create_pages',
            'edit_pages',
            'view_pages',
            'delete_pages',
            'create_layouts',
            'edit_layouts',
            'view_layouts',
            'delete_layouts',
            'create_menus',
            'edit_menus',
            'view_menus',
            'delete_menus',
            'create_folders',
            'edit_folders',
            'view_folders',
            'delete_folders',
            'create_testimonials',
            'edit_testimonials',
            'view_testimonials',
            'delete_testimonials',
        ];

        $authorPermissions = [
            'edit_posts',
            'view_posts',
            'edit_pages',
            'view_pages',
            'edit_layouts',
            'view_layouts',
            'edit_menus',
            'view_menus',
            'create_folders',
            'edit_folders',
            'view_folders',
            'delete_folders',
            'create_testimonials',
            'edit_testimonials',
            'view_testimonials',
            'delete_testimonials',
        ];

        $userPermissions = [
            'view_posts',
            'view_pages',
            'view_layouts',
            'view_menus',
        ];

        foreach (Role::all() as $role) {
            foreach (Permission::all() as $permission) {
                switch ($role->name) {
                    case 'administrator':
                        $role->givePermissionTo($permission->name);
                    case 'editor':
                        if (in_array($permission->name, $editorPermissions)) {
                            $role->givePermissionTo($permission->name);
                        }
                    case 'author':
                        if (in_array($permission->name, $authorPermissions)) {
                            $role->givePermissionTo($permission->name);
                        }
                    case 'user':
                        if (in_array($permission->name, $userPermissions)) {
                            $role->givePermissionTo($permission->name);
                        }
                    default:
                }
            }
        }
        Schema::enableForeignKeyConstraints();
    }
}
