<?php

declare(strict_types=1);

namespace Modules\Auth\Actions;

use Modules\Auth\Models\User;

class DeleteUserAction
{
    public function handle(User $user): void
    {
        $user->delete();
    }
}
