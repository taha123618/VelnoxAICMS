<?php

namespace Modules\Contacts\Actions;

use Illuminate\Http\Request;
use Modules\Contacts\Models\Contact;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class SearchContactsAction
{
    public function handle(Request $request)
    {
        return QueryBuilder::for(Contact::class)
            ->defaultSort('name', '-created_at')
            ->allowedFilters([
                AllowedFilter::partial('name'),
                AllowedFilter::partial('email'),
            ])
            ->allowedSorts('name', 'email', 'created_at')
            ->filter($request->only(['search', 'sort']))
            ->paginate($request->get('perPage', 10))
            ->withQueryString();
    }
}
