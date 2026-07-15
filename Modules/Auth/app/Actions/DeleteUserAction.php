<?php

namespace Modules\Auth\Actions;

use Modules\Auth\Models\User;

class DeleteUserAction
{
    public function handle(User $user)
    {
        $user->delete();
    }
}
