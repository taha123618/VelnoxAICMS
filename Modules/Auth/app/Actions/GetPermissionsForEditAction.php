<?php

namespace Modules\Auth\Actions;

use Modules\Auth\Models\Permission;

class GetPermissionsForEditAction
{
    public function handle()
    {

        return Permission::orderBy('name')
            ->get(['id', 'name', 'label', 'group'])
            ->groupBy('group')
            ->map(fn($groupPermissions, $groupName) => [
                'name' => $groupName,
                'permissions' => collect($groupPermissions)->map(fn($perm) =>  [
                    'id' => $perm['id'],
                    'label' => $perm['label'],
                    'name' => $perm['name'],
                ])->values()->all(),
            ])
            ->values()
            ->all();
    }
}
