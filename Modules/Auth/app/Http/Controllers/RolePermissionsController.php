<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Modules\Auth\Actions\GetPermissionsForEditAction;
use Modules\Auth\Data\RoleData;
use Modules\Auth\Http\Requests\UpdateRolePermissionsRequest;
use Modules\Auth\Models\Role;

class RolePermissionsController extends Controller
{
    public function edit(Role $role)
    {
        Gate::authorize('update', $role);

        $permissions = resolve(GetPermissionsForEditAction::class)->handle();

        return Inertia::render('Auth::roles/permissions', [
            'role' => RoleData::fromModel($role),
            'permissions' => $permissions,
        ]);
    }

    public function update(UpdateRolePermissionsRequest $updateRolePermissionsRequest, Role $role): RedirectResponse
    {
        Gate::authorize('update', $role);

        $role->syncPermissions($updateRolePermissionsRequest->permissions);

        return back();
    }
}
