<?php

namespace Modules\Ai\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Ai\Agents\ContentGenerator;
use Modules\Ai\Agents\PageSectionGenerator;
use Modules\Ai\Agents\SeoOptimizer;
use PromptPHP\Intercept\Exceptions\InterceptException;
use PromptPHP\Intercept\InjectionGuard\Exceptions\PromptInjectionGuardException;

class AiController extends Controller
{
    /**
     * Generate a page section AST using the PageSectionGenerator AI Agent.
     */
    public function generateSection(Request $request)
    {
        $request->validate([
            'prompt' => ['required', 'string', 'max:1000'],
        ]);

        try {
            $pageSectionGenerator = new PageSectionGenerator;
            $response = $pageSectionGenerator->prompt($request->input('prompt'));

            return response()->json([
                'elements' => $response['elements'] ?? [],
            ]);
        } catch (PromptInjectionGuardException|InterceptException) {
            return response()->json([
                'message' => 'Your message could not be processed because it appears to contain unsafe prompt instructions.',
            ], 422);
        }
    }

    /**
     * Generate content using ContentGenerator AI Agent.
     */
    public function generateContent(Request $request)
    {
        $request->validate([
            'prompt' => ['required', 'string', 'max:2000'],
        ]);

        try {
            $contentGenerator = new ContentGenerator;
            $response = $contentGenerator->prompt($request->input('prompt'));

            return response()->json([
                'content' => $response->text,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to generate content: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generate SEO metadata using SeoOptimizer AI Agent.
     */
    public function optimizeSeo(Request $request)
    {
        $request->validate([
            'content' => ['required', 'string', 'max:10000'],
        ]);

        try {
            $seoOptimizer = new SeoOptimizer;
            $response = $seoOptimizer->prompt('Optimize SEO for this content: '.$request->input('content'));

            return response()->json([
                'meta_title' => $response['meta_title'] ?? '',
                'meta_description' => $response['meta_description'] ?? '',
                'keywords' => $response['keywords'] ?? [],
                'suggestions' => $response['suggestions'] ?? [],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to optimize SEO: '.$e->getMessage(),
            ], 500);
        }
    }
}
