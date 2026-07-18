<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
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

    public function update(ProfileUpdateRequest $request)
    {
        app(UpdateProfileInformationAction::class)->handle($request);

        return Redirect::back();
    }
}
