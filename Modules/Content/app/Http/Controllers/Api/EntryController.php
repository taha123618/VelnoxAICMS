<?php

namespace Modules\Content\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\Content\Models\Collection;
use Modules\Content\Models\Entry;

class EntryController extends Controller
{
    public function index($collectionSlug)
    {
        $collection = Collection::where('slug', $collectionSlug)->firstOrFail();

        $entries = Entry::where('collection_id', $collection->id)
            ->where('status', 'published')
            ->paginate();

        return response()->json($entries);
    }

    public function show($collectionSlug, $id)
    {
        $collection = Collection::where('slug', $collectionSlug)->firstOrFail();

        $entry = Entry::where('collection_id', $collection->id)
            ->where('id', $id)
            ->where('status', 'published')
            ->firstOrFail();

        return response()->json($entry);
    }
}
