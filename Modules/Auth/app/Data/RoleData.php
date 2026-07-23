<?php

namespace Modules\Auth\Data;

use Illuminate\Support\Collection;
use Modules\Auth\Models\Role;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript()]
class RoleData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public string $label,
        public string $total_permissions,
        public string $total_users,
        public string $created_at,
        public array|Collection $permissions,
        public array|Optional $can,
    ) {}

    public static function fromModel(Role $role): self
    {
        return new self(
            id: $role->id,
            name: $role->name,
            label: $role->label,
            total_permissions: $role->total_permissions,
            total_users: $role->total_users,
            created_at: $role->created_at,
            permissions: $role->permissions->pluck('id'),
            can: $role->authorization
        );
    }
}
