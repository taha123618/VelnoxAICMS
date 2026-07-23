<?php

namespace Modules\Ai\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Modules\Ai\Agents\ContentGenerator;
use Modules\Ai\Agents\PageSectionGenerator;
use Modules\Ai\Agents\SeoOptimizer;
use Modules\Ai\Events\AiJobStatusUpdated;
use Modules\Ai\Models\AiGenerationJob;
use Throwable;

class ProcessAiGenerationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $backoff = 5;
    public int $timeout = 60;

    public function __construct(public string $jobId)
    {
        $this->onQueue('ai-generation');
    }

    public function handle(): void
    {
        $job = AiGenerationJob::find($this->jobId);

        if (! $job) {
            Log::error("AiGenerationJob not found: {$this->jobId}");

            return;
        }

        // Idempotency check
        if (in_array($job->status, [AiGenerationJob::STATUS_COMPLETED, AiGenerationJob::STATUS_CANCELLED])) {
            return;
        }

        try {
            // Step 1: Processing started
            $job->markAsProcessing(20);

            // Step 2: Invoke LLM Agent according to job type
            $result = match ($job->type) {
                'content' => $this->generateContent($job->prompt),
                'seo' => $this->generateSeo($job->prompt),
                default => $this->generateSection($job->prompt),
            };

            // Step 3: Update progress to 80% and broadcast update
            $job->updateProgress(80);
            $this->safeBroadcast($job);

            // Step 4: Complete Job and broadcast terminal status
            $job->markAsCompleted($result);
            $this->safeBroadcast($job);

        } catch (Throwable $e) {
            Log::error("AiGenerationJob failed: {$job->id}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $job->markAsFailed($e->getMessage());
            $this->safeBroadcast($job);

            throw $e;
        }
    }

    protected function safeBroadcast(AiGenerationJob $job): void
    {
        try {
            event(new AiJobStatusUpdated($job));
        } catch (Throwable $e) {
            Log::warning("WebSocket broadcast failed for AiGenerationJob {$job->id} (Reverb server down or unreachable): ".$e->getMessage());
        }
    }

    protected function generateSection(string $prompt): array
    {
        $agent = new PageSectionGenerator;
        $response = $agent->prompt($prompt);

        return [
            'elements' => $response['elements'] ?? [],
        ];
    }

    protected function generateContent(string $prompt): array
    {
        $agent = new ContentGenerator;
        $response = $agent->prompt($prompt);

        return [
            'content' => $response->text ?? '',
        ];
    }

    protected function generateSeo(string $prompt): array
    {
        $agent = new SeoOptimizer;
        $response = $agent->prompt($prompt);

        return [
            'meta' => $response ?? [],
        ];
    }

    public function failed(Throwable $exception): void
    {
        $job = AiGenerationJob::find($this->jobId);
        if ($job && $job->status !== AiGenerationJob::STATUS_COMPLETED) {
            $job->markAsFailed($exception->getMessage());
            $this->safeBroadcast($job);
        }
    }
}
