<?php

namespace Modules\Auth\Models;

use Illuminate\Support\Facades\Gate;
use Modules\Auth\Policies\RolePolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;

#[UsePolicy(RolePolicy::class)]
class Role extends \Spatie\Permission\Models\Role
{
    use HasUlids;

    public function getTotalUsersAttribute()
    {
        return $this->users()->count();
    }

    public function getTotalPermissionsAttribute()
    {
        return $this->load('permissions')->permissions()->count();
    }

    public function scopeFilter(Builder $query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->whereAny(['name', 'label'], 'like', "%$search%");
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }

    public function getAuthorizationAttribute()
    {
        return [
            'update' => Gate::allows('update', $this),
            'delete' => Gate::allows('delete', $this),
            'super' => $this->name == 'administrator'
        ];
    }

    
}
