<?php

namespace Modules\Testimonial\Actions;

use Illuminate\Http\Request;
use Modules\Testimonial\Models\Testimonial;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

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
