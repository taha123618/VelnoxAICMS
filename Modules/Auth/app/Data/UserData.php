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
        public int|string $id,
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

    public static function fromModel(User $user): self
    {
        return new self(
            id: $user->id,
            first_name: $user->first_name,
            last_name: $user->last_name,
            name: $user->name,
            role_name: $user->getMainRole()?->name,
            role_label: $user->getMainRole()?->label,
            role_id: $user->getMainRole()?->id,
            avatar: null,
            email: $user->email,
            created_at: $user->created_at,
            can: $user->authorization
        );
    }
}
