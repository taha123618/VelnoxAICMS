<?php

namespace Modules\Testimonial\Actions;

use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Modules\Testimonial\Models\Testimonial;

class SearchTestimonialsAction
{
    public function handle(Request $request)
    {
        return QueryBuilder::for(Testimonial::class)
            ->defaultSort('-created_at', 'name')
            ->allowedSorts('name', 'created_at')
            ->allowedFilters([
                AllowedFilter::partial('name'),
                AllowedFilter::partial('title'),
            ])
            ->filter($request->only(['search', 'sort']))
            ->paginate($request->get('perPage', 10))
            ->withQueryString();
    }
}
