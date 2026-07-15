<?php

namespace Modules\Auth\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

// use Modules\Auth\Database\Factories\PermissionFactory;

class Permission extends \Spatie\Permission\Models\Permission
{
    use HasUlids;
    
}
