<?php

namespace Modules\Content\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\Content\Models\Collection;

class CollectionController extends Controller
{
    public function index()
    {
        return response()->json(Collection::with('fields')->paginate());
    }

    public function show($slug)
    {
        return response()->json(Collection::with('fields')->where('slug', $slug)->firstOrFail());
    }
}
