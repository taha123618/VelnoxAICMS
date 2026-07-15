<?php

namespace Modules\Auth\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Redirect;
use Modules\Auth\Models\User;

class UserSendPasswordResetLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(Request $request, User $user)
    {
        Password::sendResetLink([
            'email' => $user->email
        ]);

        return Redirect::back();
    }
}
