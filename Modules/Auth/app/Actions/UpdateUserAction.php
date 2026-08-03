<?php

namespace Modules\Auth\Actions;

use Modules\Auth\Http\Requests\UpdateUserRequest;
use Modules\Auth\Models\User;

class UpdateUserAction
{
    public function handle(UpdateUserRequest $updateUserRequest, User $user)
    {

        return tap($user)->update([
            'first_name' => $updateUserRequest->first_name,
            'last_name' => $updateUserRequest->last_name,
            'email' => $updateUserRequest->email,
        ]);
    }
}
