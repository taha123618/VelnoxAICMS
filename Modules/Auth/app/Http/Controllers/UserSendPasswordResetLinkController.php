<?php

declare(strict_types=1);

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Modules\Auth\Models\User;

class UserSendPasswordResetLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(Request $request, User $user): RedirectResponse
    {
        Password::sendResetLink([
            'email' => $user->email,
        ]);

        return back();
    }
}
