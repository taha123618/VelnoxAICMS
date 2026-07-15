<?php

namespace Modules\Layout\Actions;

use Illuminate\Http\Request;
use Modules\Layout\Models\Layout;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class SearchLayoutsAction
{
    public function handle(Request $request)
    {
        return QueryBuilder::for(Layout::class)
            ->defaultSort('name', '-created_at')
            ->allowedSorts('name', 'created_at')
            ->allowedFilters([
                AllowedFilter::partial('name')
            ])
            ->filter($request->only(['search', 'sort']))
            ->paginate($request->get('perPage', 10))
            ->withQueryString();
    }
}
