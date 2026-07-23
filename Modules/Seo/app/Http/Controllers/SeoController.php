<?php

namespace Modules\Seo\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Seo\Models\SeoMeta;

class SeoController extends Controller
{
    /**
     * Store or update the SEO meta for a given model.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'seoable_type' => ['required', 'string'],
            'seoable_id' => ['required', 'integer'],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'keywords' => ['nullable', 'string', 'max:255'],
            'canonical_url' => ['nullable', 'url'],
            'og_image' => ['nullable', 'string', 'max:255'],
            'robots' => ['nullable', 'string', 'max:255'],
        ]);

        $seoMeta = SeoMeta::updateOrCreate(
            [
                'seoable_type' => $validated['seoable_type'],
                'seoable_id' => $validated['seoable_id'],
            ],
            [
                'title' => $validated['title'],
                'description' => $validated['description'],
                'keywords' => $validated['keywords'],
                'canonical_url' => $validated['canonical_url'],
                'og_image' => $validated['og_image'],
                'robots' => $validated['robots'] ?? 'index, follow',
            ]
        );

        if ($request->wantsJson()) {
            return response()->json(['message' => 'SEO saved successfully.', 'data' => $seoMeta]);
        }

        return back()->with('success', 'SEO saved successfully.');
    }
}
