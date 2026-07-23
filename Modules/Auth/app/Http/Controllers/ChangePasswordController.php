<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Modules\Auth\Actions\ChangeUserPasswordAction;
use Modules\Auth\Http\Requests\PasswordUpdateRequest;

class ChangePasswordController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        return Inertia::render('Auth::settings/password');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PasswordUpdateRequest $request)
    {
        app(ChangeUserPasswordAction::class)->handle($request);

        return Redirect::back();
    }
}
