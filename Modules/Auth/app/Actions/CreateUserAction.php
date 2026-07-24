<?php

namespace Modules\Auth\Actions;

use Modules\Auth\Http\Requests\CreateUserRequest;
use Modules\Auth\Models\User;

class CreateUserAction
{
    public function handle(CreateUserRequest $createUserRequest)
    {

        return User::create([
            'first_name' => $createUserRequest->first_name,
            'last_name' => $createUserRequest->last_name,
            'email' => $createUserRequest->email,
            'password' => bcrypt($createUserRequest->password),
        ]);
    }
}
