<?php

declare(strict_types=1);

namespace Modules\Workflow\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Ai\Services\AiGenerationService;
use Modules\Workflow\Agents\WorkflowGenerator;
use PromptPHP\Intercept\Exceptions\InterceptException;
use PromptPHP\Intercept\InjectionGuard\Exceptions\PromptInjectionGuardException;

class AiWorkflowController extends Controller
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
                type: 'workflow',
                userId: $request->user()?->id
            );

            return response()->json([
                'message' => 'AI workflow generation queued successfully.',
                'job' => $job,
            ], 202);
        }

        try {
            $workflowGenerator = new WorkflowGenerator;
            $response = $workflowGenerator->prompt($request->input('prompt'));

            $this->aiService->recordCompletedJob(
                prompt: $request->input('prompt'),
                type: 'workflow',
                result: ['workflow' => $response],
                userId: $request->user()?->id
            );

            return response()->json([
                'workflow' => $response,
            ]);
        } catch (PromptInjectionGuardException|InterceptException) {
            return response()->json([
                'message' => 'Your request could not be processed because it appears to contain unsafe prompt instructions.',
            ], 422);
        }
    }
}
