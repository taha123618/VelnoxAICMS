<?php

namespace Modules\Auth\Actions;

use Modules\Auth\Http\Requests\ProfileUpdateRequest;

class UpdateProfileInformationAction
{
    public function handle(ProfileUpdateRequest $request)
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();
    }
}
