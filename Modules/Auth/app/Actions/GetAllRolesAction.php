<?php

namespace Modules\Auth\Actions;

use Modules\Auth\Models\Role;

class GetAllRolesAction
{
    public function handle()
    {
        return Role::query()->get();
    }
}
