<?php

namespace Modules\Ai\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Ai\Services\AiGenerationService;

class AiJobController extends Controller
{
    public function __construct(protected AiGenerationService $service) {}

    /**
     * Dispatch an asynchronous AI generation job.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'prompt' => ['required', 'string', 'max:2000'],
            'type' => ['nullable', 'string', 'in:section,content,seo'],
        ]);

        $aiGenerationJob = $this->service->dispatchJob(
            prompt: $validated['prompt'],
            type: $validated['type'] ?? 'section',
            userId: $request->user()?->id
        );

        return response()->json([
            'message' => 'AI generation job queued successfully.',
            'job' => $aiGenerationJob,
        ], 202);
    }

    /**
     * Get status of an AI generation job.
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $aiGenerationJob = $this->service->getJob($id, $request->user()?->id);

        return response()->json([
            'job' => $aiGenerationJob,
        ]);
    }

    /**
     * Retry a failed AI generation job.
     */
    public function retry(Request $request, string $id): JsonResponse
    {
        $aiGenerationJob = $this->service->retryJob($id, $request->user()?->id);

        return response()->json([
            'message' => 'AI generation job retried successfully.',
            'job' => $aiGenerationJob,
        ], 202);
    }

    /**
     * Cancel an active AI generation job.
     */
    public function cancel(Request $request, string $id): JsonResponse
    {
        $aiGenerationJob = $this->service->cancelJob($id, $request->user()?->id);

        return response()->json([
            'message' => 'AI generation job cancelled successfully.',
            'job' => $aiGenerationJob,
        ]);
    }
}
