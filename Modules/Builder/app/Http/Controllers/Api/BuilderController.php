<?php

namespace Modules\Builder\Http\Controllers\Api;

use App\Events\PageUpdated;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Page\Models\Page;

class BuilderController extends Controller
{
    /**
     * Get the JSON AST for a specific page.
     */
    public function show(Page $page)
    {
        return response()->json([
            'data' => [
                'id' => $page->id,
                'title' => $page->title,
                'slug' => $page->slug,
                'content' => $page->content ?? [], // This is where we store the AST
                'is_published' => $page->is_published,
                'versions' => $page->versions()->latest()->take(10)->get(),
            ],
        ]);
    }

    /**
     * Update the JSON AST for a specific page.
     */
    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'content' => 'required|array',
        ]);

        $page->update([
            'content' => $validated['content'],
        ]);

        // Broadcast event for real-time collaboration via Reverb
        if (class_exists(PageUpdated::class)) {
            broadcast(new PageUpdated($page))->toOthers();
        }

        return response()->json([
            'message' => 'Page content updated successfully',
            'data' => [
                'id' => $page->id,
                'content' => $page->content,
            ],
        ]);
    }
}
