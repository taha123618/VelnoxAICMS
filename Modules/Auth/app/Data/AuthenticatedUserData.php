<?php

namespace Modules\Auth\Data;

use Illuminate\Support\Facades\Gate;
use Spatie\LaravelData\Data;
use Modules\Auth\Models\User;
use Modules\Page\Models\Page;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript()]
class AuthenticatedUserData extends Data
{
    public function __construct(
        public string $id,
        public string $first_name,
        public string $last_name,
        public string $name,
        public string $email,
        public bool $isVerified,
        public string|null $avatar,
        public AuthorizationData $can
    ) {}

    public static function fromModel(User $user): self
    {
        return new self(
            id: $user->id,
            first_name: $user->first_name,
            last_name: $user->last_name,
            name: $user->name,
            email: $user->email,
            isVerified: $user->hasVerifiedEmail(),
            avatar: null,
            can: AuthorizationData::fromModel($user)
        );
    }
}
