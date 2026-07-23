<?php

namespace Modules\Content\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Content\Models\Collection;
use Modules\Content\Models\Entry;

class EntryController extends Controller
{
    public function index(Collection $collection)
    {
        $entries = $collection->entries()->latest()->get();
        return Inertia::render('Content::entries/index', [
            'collection' => $collection,
            'entries' => $entries
        ]);
    }

    public function create(Collection $collection)
    {
        return Inertia::render('Content::entries/create', [
            'collection' => $collection->load('fields')
        ]);
    }

    public function store(Request $request, Collection $collection)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'status' => 'required|string',
            'data' => 'nullable|array',
        ]);

        $collection->entries()->create($validated);

        return redirect()->route('admin.collections.entries.index', $collection)->with('success', 'Entry created successfully.');
    }

    public function edit(Collection $collection, Entry $entry)
    {
        return Inertia::render('Content::entries/edit', [
            'collection' => $collection->load('fields'),
            'entry' => $entry
        ]);
    }

    public function update(Request $request, Collection $collection, Entry $entry)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'status' => 'required|string',
            'data' => 'nullable|array',
        ]);

        $entry->update($validated);

        return redirect()->route('admin.collections.entries.index', $collection)->with('success', 'Entry updated successfully.');
    }

    public function destroy(Collection $collection, Entry $entry)
    {
        $entry->delete();

        return redirect()->back()->with('success', 'Entry deleted successfully.');
    }
}
