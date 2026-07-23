<?php

declare(strict_types=1);

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
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
    public function update(PasswordUpdateRequest $passwordUpdateRequest): RedirectResponse
    {
        resolve(ChangeUserPasswordAction::class)->handle($passwordUpdateRequest);

        return back();
    }
}
