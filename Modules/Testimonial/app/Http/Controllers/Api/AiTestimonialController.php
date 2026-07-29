<?php

declare(strict_types=1);

namespace Modules\Testimonial\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Ai\Jobs\ProcessAiGenerationJob;
use Modules\Ai\Models\AiGenerationJob;
use Modules\Testimonial\Agents\TestimonialGenerator;
use Modules\Testimonial\Models\Testimonial;
use Throwable;

class AiTestimonialController extends Controller
{
    public function __invoke(Request $request, TestimonialGenerator $generator): JsonResponse
    {
        $validated = $request->validate([
            'prompt' => ['required', 'string', 'min:3', 'max:1000'],
            'async' => ['sometimes', 'boolean'],
        ]);

        $async = $request->boolean('async', false);
        $userId = $request->user()?->id;

        if ($async) {
            $existingActiveJob = AiGenerationJob::where('user_id', $userId)
                ->where('type', 'testimonial')
                ->whereIn('status', [AiGenerationJob::STATUS_QUEUED, AiGenerationJob::STATUS_PROCESSING])
                ->first();

            if ($existingActiveJob) {
                return response()->json([
                    'success' => false,
                    'message' => 'An active testimonial generation job is already in progress.',
                    'job' => $existingActiveJob,
                ], 429);
            }

            $jobRecord = AiGenerationJob::create([
                'user_id' => $userId,
                'type' => 'testimonial',
                'prompt' => $validated['prompt'],
                'status' => AiGenerationJob::STATUS_QUEUED,
                'progress' => 0,
            ]);

            dispatch(new ProcessAiGenerationJob($jobRecord->id));

            return response()->json([
                'success' => true,
                'message' => 'Testimonial generation job queued successfully.',
                'job_id' => $jobRecord->id,
                'status' => $jobRecord->status,
                'job' => $jobRecord,
            ], 202);
        }

        try {
            $result = $generator->generate($validated['prompt']);

            if (! empty($result['testimonial'])) {
                $item = $result['testimonial'];
                Testimonial::create([
                    'name' => $item['name'] ?? 'AI Customer',
                    'title' => $item['title'] ?? $item['company'] ?? 'Verified Customer',
                    'comment' => $item['comment'] ?? 'Outstanding experience!',
                    'avatar' => $item['avatar'] ?? null,
                    'published_at' => now(),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Testimonial generated successfully.',
                'result' => $result,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate testimonial: '.$e->getMessage(),
            ], 500);
        }
    }
}
