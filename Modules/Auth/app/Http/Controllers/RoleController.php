<?php

namespace Modules\Auth\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Modules\Auth\Models\Role;
use Modules\Auth\Data\RoleData;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Modules\Auth\Actions\SearchRolesAction;
use Modules\Auth\Http\Requests\CreateRoleRequest;
use Modules\Auth\Http\Requests\UpdateRoleRequest;

class RoleController extends Controller
{

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'sort']);

        $data = app(SearchRolesAction::class)->handle($request);

        return Inertia::render('Auth::roles/index', [
            'data' => RoleData::collect($data),
            'filters' => $filters,
        ]);
    }

    public function create()
    {
        Gate::authorize('create', Role::class);

        return Inertia::render('Auth::roles/create');
    }

    public function store(CreateRoleRequest $request)
    {
        Gate::authorize('create', Role::class);

        Role::create([
            'name' => Str::slug($request->name),
            'label' => $request->name,
            'guard_name' => 'web'
        ]);

        return Redirect::back();
    }

    public function edit(Role $role)
    {
        Gate::authorize('update', $role);

        return Inertia::render('Auth::roles/edit', [
            'role' => RoleData::fromModel($role)
        ]);
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        Gate::authorize('update', $role);

        $role->update([
            'label' => $request->label
        ]);

        return Redirect::back();
    }


    public function destroy(Role $role)
    {
        Gate::authorize('delete', $role);

        if ($role->total_users == 0) {
            $role->delete();
        }

        return Redirect::back();
    }
}
