<?php

namespace Modules\Auth\Actions;

use Modules\Auth\Models\User;
use Modules\Auth\Http\Requests\CreateUserRequest;

class CreateUserAction
{
    public function handle(CreateUserRequest $request)
    {

        return User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
    }
}
