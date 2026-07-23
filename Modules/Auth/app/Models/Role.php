<?php

namespace Modules\Auth\Models;

use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Support\Facades\Gate;
use Modules\Auth\Policies\RolePolicy;

#[UsePolicy(RolePolicy::class)]
class Role extends \Spatie\Permission\Models\Role
{
    use HasUlids;

    protected function getTotalUsersAttribute()
    {
        return $this->users()->count();
    }

    protected function getTotalPermissionsAttribute()
    {
        return $this->load('permissions')->permissions()->count();
    }

    protected function scopeFilter(Builder $builder, array $filters): void
    {
        $builder->when($filters['search'] ?? null, function ($query, $search): void {
            $query->whereAny(['name', 'label'], 'like', "%$search%");
        })->when($filters['trashed'] ?? null, function ($query, $trashed): void {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }

    protected function getAuthorizationAttribute(): array
    {
        return [
            'update' => Gate::allows('update', $this),
            'delete' => Gate::allows('delete', $this),
            'super' => $this->name == 'administrator',
        ];
    }
}
