<?php

namespace Modules\Ai\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Modules\Ai\Events\AiJobStatusUpdated;
use Modules\Ai\Jobs\ProcessAiGenerationJob;
use Modules\Ai\Models\AiGenerationJob;
use Throwable;

class AiGenerationService
{
    /**
     * Queue a new AI generation job.
     */
    public function dispatchJob(string $prompt, string $type = 'section', ?string $userId = null): AiGenerationJob
    {
        // Prevent duplicate active submissions for the same user and type
        if ($userId) {
            $activeJobs = AiGenerationJob::where('user_id', $userId)
                ->where('type', $type)
                ->whereIn('status', [AiGenerationJob::STATUS_QUEUED, AiGenerationJob::STATUS_PROCESSING])
                ->get();

            foreach ($activeJobs as $existingJob) {
                if ($existingJob->updated_at && $existingJob->updated_at->diffInMinutes(now()) >= 2) {
                    $existingJob->markAsFailed('Job timed out due to queue inactivity.');
                } else {
                    throw ValidationException::withMessages([
                        'prompt' => ['An AI generation job is already processing. Please wait for it to complete.'],
                    ]);
                }
            }
        }

        $job = AiGenerationJob::create([
            'user_id' => $userId,
            'type' => $type,
            'prompt' => $prompt,
            'status' => AiGenerationJob::STATUS_QUEUED,
            'progress' => 0,
        ]);

        // Dispatch background queue job
        ProcessAiGenerationJob::dispatch($job->id);

        return $job;
    }

    /**
     * Get job status by ID.
     */
    public function getJob(string $jobId, ?string $userId = null): AiGenerationJob
    {
        $query = AiGenerationJob::where('id', $jobId);

        if ($userId) {
            $query->where('user_id', $userId);
        }

        return $query->firstOrFail();
    }

    /**
     * Retry a failed job.
     */
    public function retryJob(string $jobId, ?string $userId = null): AiGenerationJob
    {
        $job = $this->getJob($jobId, $userId);

        $job->update([
            'status' => AiGenerationJob::STATUS_QUEUED,
            'progress' => 0,
            'error' => null,
            'result' => null,
        ]);

        $this->safeBroadcast($job);

        ProcessAiGenerationJob::dispatch($job->id);

        return $job;
    }

    /**
     * Cancel an active AI generation job.
     */
    public function cancelJob(string $jobId, ?string $userId = null): AiGenerationJob
    {
        $job = $this->getJob($jobId, $userId);

        if (in_array($job->status, [AiGenerationJob::STATUS_COMPLETED, AiGenerationJob::STATUS_FAILED])) {
            return $job;
        }

        $job->update([
            'status' => AiGenerationJob::STATUS_CANCELLED,
            'error' => 'Job was cancelled by the user.',
        ]);

        $this->safeBroadcast($job);

        return $job;
    }

    protected function safeBroadcast(AiGenerationJob $job): void
    {
        try {
            event(new AiJobStatusUpdated($job));
        } catch (Throwable $e) {
            Log::warning("WebSocket broadcast failed for AiGenerationJob {$job->id} (Reverb server down or unreachable): ".$e->getMessage());
        }
    }
}
