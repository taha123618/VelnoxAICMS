<?php

declare(strict_types=1);

namespace Modules\Auth\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;

// use Modules\Auth\Database\Factories\PermissionFactory;

class Permission extends \Spatie\Permission\Models\Permission
{
    use HasUlids;
}
