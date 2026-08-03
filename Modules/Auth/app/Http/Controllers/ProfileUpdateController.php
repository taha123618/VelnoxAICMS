<?php

declare(strict_types=1);

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Auth\Actions\UpdateProfileInformationAction;
use Modules\Auth\Http\Requests\ProfileUpdateRequest;

class ProfileUpdateController extends Controller
{
    public function edit(Request $request)
    {
        return Inertia::render('Auth::settings/profile', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => $request->session()->get('status'),
        ]);
    }

    public function update(ProfileUpdateRequest $profileUpdateRequest): RedirectResponse
    {
        resolve(UpdateProfileInformationAction::class)->handle($profileUpdateRequest);

        return back();
    }
}
