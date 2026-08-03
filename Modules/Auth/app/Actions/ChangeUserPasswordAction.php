<?php

namespace Modules\Auth\Actions;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ChangeUserPasswordAction
{
    public function handle(Request $request): void
    {
        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);
    }
}
