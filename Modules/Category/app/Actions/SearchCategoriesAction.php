<?php

namespace Modules\Category\Actions;

use Illuminate\Http\Request;
use Modules\Category\Models\Category;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SearchCategoriesAction
{
    public function handle(Request $request)
    {
        return QueryBuilder::for(Category::class)
            ->defaultSort('name', '-created_at')
            ->allowedFilters([
                AllowedFilter::partial('name'),
            ])
            ->allowedSorts('name', 'created_at')
            ->filter($request->only(['search', 'sort']))
            ->with('parent')
            ->paginate($request->get('perPage', 10))
            ->withQueryString();
    }
}
