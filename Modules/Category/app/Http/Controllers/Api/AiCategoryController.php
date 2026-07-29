<?php

declare(strict_types=1);

namespace Modules\Category\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Ai\Services\AiGenerationService;
use Modules\Category\Agents\CategoryTaxonomyGenerator;
use Modules\Category\Models\Category;
use PromptPHP\Intercept\Exceptions\InterceptException;
use PromptPHP\Intercept\InjectionGuard\Exceptions\PromptInjectionGuardException;

class AiCategoryController extends Controller
{
    public function __construct(protected AiGenerationService $aiService) {}

    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'prompt' => ['required', 'string', 'max:2000'],
            'async' => ['nullable', 'boolean'],
        ]);

        // Support async queued mode by default unless explicitly disabled with async=false
        if ($request->boolean('async', false)) {
            $job = $this->aiService->dispatchJob(
                prompt: $request->input('prompt'),
                type: 'category',
                userId: $request->user()?->id
            );

            return response()->json([
                'message' => 'AI category taxonomy generation queued successfully.',
                'job' => $job,
            ], 202);
        }

        try {
            $categoryTaxonomyGenerator = new CategoryTaxonomyGenerator;
            $response = $categoryTaxonomyGenerator->prompt($request->input('prompt'));

            if (! empty($response['category_name']) || ! empty($response['name'])) {
                $name = $response['category_name'] ?? $response['name'] ?? 'AI Category';
                Category::create([
                    'name' => $name,
                    'slug' => Str::slug($name.'-'.time()),
                ]);
            }

            return response()->json([
                'taxonomy' => $response,
            ]);
        } catch (PromptInjectionGuardException|InterceptException) {
            return response()->json([
                'message' => 'Your request could not be processed because it appears to contain unsafe prompt instructions.',
            ], 422);
        }
    }
}
