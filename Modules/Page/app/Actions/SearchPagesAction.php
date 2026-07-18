<?php

namespace Modules\Page\Actions;

use Illuminate\Http\Request;
use Modules\Page\Models\Page;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SearchPagesAction
{
    public function handle(Request $request)
    {
        return QueryBuilder::for(Page::class)
            ->defaultSort('-is_frontpage', 'title', '-created_at')
            ->allowedFilters([
                AllowedFilter::partial('title'),
                AllowedFilter::partial('slug'),
            ])
            ->allowedSorts('title', 'created_at')
            ->pages()
            ->filter($request->only(['search', 'sort']))
            ->paginate($request->get('perPage', 10))
            ->withQueryString();
    }
}
