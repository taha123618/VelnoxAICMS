<?php

namespace Modules\Auth\Data;

use Modules\Auth\Models\User;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript()]
class UserData extends Data
{
    public function __construct(
        public string $id,
        public string $first_name,
        public string $last_name,
        public string $name,
        public ?string $role_name,
        public ?string $role_label,
        public ?string $role_id,
        public ?string $avatar,
        public string $email,
        public string $created_at,
        public array|Optional $can
    ) {}

    public static function fromModel(User $model): self
    {
        return new self(
            id: $model->id,
            first_name: $model->first_name,
            last_name: $model->last_name,
            name: $model->name,
            avatar: null,
            role_name: $model->getMainRole()?->name,
            role_label: $model->getMainRole()?->label,
            role_id: $model->getMainRole()?->id,
            email: $model->email,
            created_at: $model->created_at,
            can: $model->authorization
        );
    }
}
