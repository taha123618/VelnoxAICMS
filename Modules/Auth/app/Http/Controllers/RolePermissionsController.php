<?php

namespace Modules\Auth\Http\Controllers;

use Inertia\Inertia;
use Modules\Auth\Models\Role;
use Modules\Auth\Data\RoleData;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Modules\Auth\Actions\GetPermissionsForEditAction;
use Modules\Auth\Http\Requests\UpdateRolePermissionsRequest;

class RolePermissionsController extends Controller
{

    public function edit(Role $role)
    {
        Gate::authorize('update', $role);

        $permissions = app(GetPermissionsForEditAction::class)->handle();

        return Inertia::render('Auth::roles/permissions', [
            'role' => RoleData::fromModel($role),
            'permissions' => $permissions
        ]);
    }

    public function update(UpdateRolePermissionsRequest $request, Role $role)
    {
        Gate::authorize('update', $role);
        
        $role->syncPermissions($request->permissions);

        return Redirect::back();
    }
}
