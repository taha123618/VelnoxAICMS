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

    public static function fromModel(Role $model): self
    {
        return new self(
            id: $model->id,
            name: $model->name,
            label: $model->label,
            total_users: $model->total_users,
            total_permissions: $model->total_permissions,
            created_at: $model->created_at,
            permissions: $model->permissions->pluck('id'),
            can: $model->authorization
        );
    }
}
