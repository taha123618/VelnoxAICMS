<?php

namespace Modules\Auth\Actions;

use Illuminate\Http\Request;
use Modules\Auth\Models\Role;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SearchRolesAction
{
    public function handle(Request $request)
    {
        return QueryBuilder::for(Role::class)
            ->defaultSort('name', '-created_at')
            ->allowedSorts('name', 'created_at')
            ->allowedFilters([
                AllowedFilter::partial('name'),
                AllowedFilter::partial('label'),
            ])
            ->filter($request->only(['search', 'sort']))
            ->with('permissions')
            ->paginate($request->get('perPage', 10))
            ->withQueryString();
    }
}
