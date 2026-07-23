<?php

namespace Modules\Content\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Content\Models\Collection;

class CollectionController extends Controller
{
    public function index()
    {
        $collections = Collection::latest()->get();
        return Inertia::render('Content::collections/index', [
            'collections' => $collections
        ]);
    }

    public function create()
    {
        return Inertia::render('Content::collections/create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_publishable' => 'boolean',
        ]);

        Collection::create($validated);

        return redirect()->route('admin.collections.index')->with('success', 'Collection created successfully.');
    }

    public function edit(Collection $collection)
    {
        return Inertia::render('Content::collections/edit', [
            'collection' => $collection
        ]);
    }

    public function update(Request $request, Collection $collection)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_publishable' => 'boolean',
        ]);

        $collection->update($validated);

        return redirect()->route('admin.collections.index')->with('success', 'Collection updated successfully.');
    }

    public function destroy(Collection $collection)
    {
        $collection->delete();

        return redirect()->route('admin.collections.index')->with('success', 'Collection deleted successfully.');
    }
}
