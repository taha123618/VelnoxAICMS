<?php

namespace Modules\Auth\Actions;

use Modules\Auth\Http\Requests\ProfileUpdateRequest;

class UpdateProfileInformationAction
{
    public function handle(ProfileUpdateRequest $profileUpdateRequest): void
    {
        $profileUpdateRequest->user()->fill($profileUpdateRequest->validated());

        if ($profileUpdateRequest->user()->isDirty('email')) {
            $profileUpdateRequest->user()->email_verified_at = null;
        }

        $profileUpdateRequest->user()->save();
    }
}
