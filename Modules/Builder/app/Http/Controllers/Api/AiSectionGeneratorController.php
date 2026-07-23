<?php

namespace Modules\Builder\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Ai\Services\AiGenerationService;
use Modules\Builder\Agents\PageSectionGenerator;
use PromptPHP\Intercept\Exceptions\InterceptException;
use PromptPHP\Intercept\InjectionGuard\Exceptions\PromptInjectionGuardException;

class AiSectionGeneratorController extends Controller
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
                type: 'section',
                userId: $request->user()?->id
            );

            return response()->json([
                'message' => 'AI section generation queued successfully.',
                'job' => $job,
            ], 202);
        }

        try {
            $agent = new PageSectionGenerator;
            $response = $agent->prompt($request->input('prompt'));

            return response()->json([
                'elements' => $response['elements'] ?? [],
            ]);
        } catch (PromptInjectionGuardException|InterceptException $e) {
            return response()->json([
                'message' => 'Your message could not be processed because it appears to contain unsafe prompt instructions.',
            ], 422);
        }
    }
}
