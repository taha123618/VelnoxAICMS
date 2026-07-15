<?php

namespace Modules\Auth\Actions;

use Modules\Auth\Models\User;
use Modules\Auth\Http\Requests\UpdateUserRequest;

class UpdateUserAction
{
    public function handle(UpdateUserRequest $request, User $user)
    {

        return tap($user)->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email
        ]);
    }
}
