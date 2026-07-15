<?php

namespace Modules\Auth\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Modules\Auth\Models\Role;
use Modules\Auth\Models\User;
use Modules\Auth\Data\RoleData;
use Modules\Auth\Data\UserData;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Auth\Events\Registered;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Redirect;
use Modules\Auth\Actions\CreateUserAction;
use Modules\Auth\Actions\DeleteUserAction;
use Modules\Auth\Actions\UpdateUserAction;
use Modules\Auth\Actions\GetAllRolesAction;
use Modules\Auth\Actions\SearchUsersAction;
use Modules\Auth\Http\Requests\CreateUserRequest;
use Modules\Auth\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'sort']);

        $data = app(SearchUsersAction::class)->handle($request);

        return Inertia::render('Auth::users/index', [
            'data' => UserData::collect($data),
            'filters' => $filters
        ]);
    }

    public function create()
    {
        Gate::authorize('create', User::class);
        
        $roles = app(GetAllRolesAction::class)->handle();

        return Inertia::render('Auth::users/create', [
            'roles' => RoleData::collect($roles)
        ]);
    }

    public function store(CreateUserRequest $request)
    {
        Gate::authorize('create', User::class);

        $user = app(CreateUserAction::class)->handle($request);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        Password::sendResetLink(
            ['email' => $request->email]
        );

        $user->syncRoles($request->role);

        return Redirect::back();
    }


    public function edit(User $user)
    {
        Gate::authorize('update', $user);

        $roles = app(GetAllRolesAction::class)->handle();

        return Inertia::render('Auth::users/edit', [
            'user' => UserData::fromModel($user),
            'roles' => RoleData::collect($roles)
        ]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        Gate::authorize('update', $user);
        
        app(UpdateUserAction::class)->handle($request, $user);
        
        $user->syncRoles($request->role);

        return Redirect::back();
    }

    public function destroy(User $user)
    {
        Gate::authorize('delete', $user);

        app(DeleteUserAction::class)->handle($user);

        return Redirect::back();
    }
}
