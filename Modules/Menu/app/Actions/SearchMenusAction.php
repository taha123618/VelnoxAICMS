<?php

namespace Modules\Menu\Actions;

use Illuminate\Http\Request;
use Modules\Menu\Models\Menu;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SearchMenusAction
{
    public function handle(Request $request)
    {
        return QueryBuilder::for(Menu::class)
            ->defaultSort('name', '-created_at')
            ->allowedFilters([
                AllowedFilter::partial('name'),
            ])
            ->allowedSorts('name', 'created_at')
            ->filter($request->only(['search', 'sort']))
            ->paginate($request->get('perPage', 10))
            ->withQueryString();
    }
}
