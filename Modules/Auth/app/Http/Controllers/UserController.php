<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Password;
use Inertia\Inertia;
use Modules\Auth\Actions\CreateUserAction;
use Modules\Auth\Actions\DeleteUserAction;
use Modules\Auth\Actions\GetAllRolesAction;
use Modules\Auth\Actions\SearchUsersAction;
use Modules\Auth\Actions\UpdateUserAction;
use Modules\Auth\Data\RoleData;
use Modules\Auth\Data\UserData;
use Modules\Auth\Http\Requests\CreateUserRequest;
use Modules\Auth\Http\Requests\UpdateUserRequest;
use Modules\Auth\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'sort']);

        $data = resolve(SearchUsersAction::class)->handle($request);

        return Inertia::render('Auth::users/index', [
            'data' => UserData::collect($data),
            'filters' => $filters,
        ]);
    }

    public function create()
    {
        Gate::authorize('create', User::class);

        $roles = resolve(GetAllRolesAction::class)->handle();

        return Inertia::render('Auth::users/create', [
            'roles' => RoleData::collect($roles),
        ]);
    }

    public function store(CreateUserRequest $createUserRequest): RedirectResponse
    {
        Gate::authorize('create', User::class);

        $user = resolve(CreateUserAction::class)->handle($createUserRequest);

        $user = User::create([
            'first_name' => $createUserRequest->first_name,
            'last_name' => $createUserRequest->last_name,
            'email' => $createUserRequest->email,
            'password' => bcrypt($createUserRequest->password),
        ]);

        Password::sendResetLink(
            ['email' => $createUserRequest->email]
        );

        $user->syncRoles($createUserRequest->role);

        return back();
    }

    public function edit(User $user)
    {
        Gate::authorize('update', $user);

        $roles = resolve(GetAllRolesAction::class)->handle();

        return Inertia::render('Auth::users/edit', [
            'user' => UserData::fromModel($user),
            'roles' => RoleData::collect($roles),
        ]);
    }

    public function update(UpdateUserRequest $updateUserRequest, User $user): RedirectResponse
    {
        Gate::authorize('update', $user);

        resolve(UpdateUserAction::class)->handle($updateUserRequest, $user);

        $user->syncRoles($updateUserRequest->role);

        return back();
    }

    public function destroy(User $user): RedirectResponse
    {
        Gate::authorize('delete', $user);

        resolve(DeleteUserAction::class)->handle($user);

        return back();
    }
}
