<?php

namespace Modules\Auth\Actions;

use Illuminate\Http\Request;
use Modules\Auth\Models\User;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class SearchUsersAction
{
    public function handle(Request $request) {

        return QueryBuilder::for(subject: User::class)
            ->defaultSort('first_name', 'last_name', '-created_at')
            ->allowedSorts('first_name', 'last_name', 'email', 'created_at')
            ->allowedFilters([
                AllowedFilter::partial('first_name'),
                AllowedFilter::partial('last_name'),
                AllowedFilter::partial('email'),
            ])
            ->filter($request->only(['search', 'sort']))
            ->paginate($request->get('perPage', 10))
            ->withQueryString();

    }
}
