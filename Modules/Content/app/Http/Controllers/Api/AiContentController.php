<?php

declare(strict_types=1);

namespace Modules\Content\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Ai\Services\AiGenerationService;
use Modules\Content\Agents\ArticleGenerator;
use Modules\Content\Models\Collection;
use Modules\Content\Models\Entry;
use PromptPHP\Intercept\Exceptions\InterceptException;
use PromptPHP\Intercept\InjectionGuard\Exceptions\PromptInjectionGuardException;

class AiContentController extends Controller
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
                type: 'content',
                userId: $request->user()?->id
            );

            return response()->json([
                'message' => 'AI article generation queued successfully.',
                'job' => $job,
            ], 202);
        }

        try {
            $articleGenerator = new ArticleGenerator;
            $response = $articleGenerator->prompt($request->input('prompt'));

            if (! empty($response['title'])) {
                $collectionId = $request->input('collection_id') ?? Collection::first()?->id;
                if ($collectionId) {
                    Entry::create([
                        'collection_id' => $collectionId,
                        'user_id' => $request->user()?->id,
                        'title' => $response['title'],
                        'slug' => Str::slug($response['title']),
                        'data' => [
                            'content' => $response['content'] ?? '',
                            'excerpt' => $response['excerpt'] ?? '',
                        ],
                        'status' => 'draft',
                    ]);
                }
            }

            $this->aiService->recordCompletedJob(
                prompt: $request->input('prompt'),
                type: 'content',
                result: ['article' => $response],
                userId: $request->user()?->id
            );

            return response()->json([
                'article' => $response,
            ]);
        } catch (PromptInjectionGuardException|InterceptException) {
            return response()->json([
                'message' => 'Your request could not be processed because it appears to contain unsafe prompt instructions.',
            ], 422);
        }
    }
}
