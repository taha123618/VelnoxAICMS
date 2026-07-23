<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Modules\Auth\Actions\SearchRolesAction;
use Modules\Auth\Data\RoleData;
use Modules\Auth\Http\Requests\CreateRoleRequest;
use Modules\Auth\Http\Requests\UpdateRoleRequest;
use Modules\Auth\Models\Role;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'sort']);

        $data = resolve(SearchRolesAction::class)->handle($request);

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

    public function store(CreateRoleRequest $createRoleRequest): RedirectResponse
    {
        Gate::authorize('create', Role::class);

        Role::create([
            'name' => Str::slug($createRoleRequest->name),
            'label' => $createRoleRequest->name,
            'guard_name' => 'web',
        ]);

        return back();
    }

    public function edit(Role $role)
    {
        Gate::authorize('update', $role);

        return Inertia::render('Auth::roles/edit', [
            'role' => RoleData::fromModel($role),
        ]);
    }

    public function update(UpdateRoleRequest $updateRoleRequest, Role $role): RedirectResponse
    {
        Gate::authorize('update', $role);

        $role->update([
            'label' => $updateRoleRequest->label,
        ]);

        return back();
    }

    public function destroy(Role $role): RedirectResponse
    {
        Gate::authorize('delete', $role);

        if ($role->total_users == 0) {
            $role->delete();
        }

        return back();
    }
}
