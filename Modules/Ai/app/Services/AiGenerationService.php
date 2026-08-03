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
    public function dispatchJob(string $prompt, string $type = 'section', int|string|null $userId = null): AiGenerationJob
    {
        // Prevent duplicate active submissions for the same user and type
        if ($userId) {
            $activeJobs = AiGenerationJob::where('user_id', $userId)
                ->where('type', $type)
                ->whereIn('status', [AiGenerationJob::STATUS_QUEUED, AiGenerationJob::STATUS_PROCESSING])
                ->get();

            foreach ($activeJobs as $activeJob) {
                if ($activeJob->updated_at && $activeJob->updated_at->diffInMinutes(now()) >= 2) {
                    $activeJob->markAsFailed('Job timed out due to queue inactivity.');
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
        dispatch(new ProcessAiGenerationJob($job->id));

        return $job;
    }

    /**
     * Get job status by ID.
     */
    public function getJob(int|string $jobId, int|string|null $userId = null): AiGenerationJob
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
    public function retryJob(int|string $jobId, int|string|null $userId = null): AiGenerationJob
    {
        $aiGenerationJob = $this->getJob($jobId, $userId);

        $aiGenerationJob->update([
            'status' => AiGenerationJob::STATUS_QUEUED,
            'progress' => 0,
            'error' => null,
            'result' => null,
        ]);

        $this->safeBroadcast($aiGenerationJob);

        dispatch(new ProcessAiGenerationJob($aiGenerationJob->id));

        return $aiGenerationJob;
    }

    /**
     * Cancel an active AI generation job.
     */
    public function cancelJob(int|string $jobId, int|string|null $userId = null): AiGenerationJob
    {
        $aiGenerationJob = $this->getJob($jobId, $userId);

        if (in_array($aiGenerationJob->status, [AiGenerationJob::STATUS_COMPLETED, AiGenerationJob::STATUS_FAILED])) {
            return $aiGenerationJob;
        }

        $aiGenerationJob->update([
            'status' => AiGenerationJob::STATUS_CANCELLED,
            'error' => 'Job was cancelled by the user.',
        ]);

        $this->safeBroadcast($aiGenerationJob);

        return $aiGenerationJob;
    }

    /**
     * Record a synchronous completed AI generation job into the database.
     */
    public function recordCompletedJob(string $prompt, string $type, array $result, int|string|null $userId = null): AiGenerationJob
    {
        return AiGenerationJob::create([
            'user_id' => $userId,
            'type' => $type,
            'prompt' => $prompt,
            'status' => AiGenerationJob::STATUS_COMPLETED,
            'progress' => 100,
            'result' => $result,
        ]);
    }

    protected function safeBroadcast(AiGenerationJob $aiGenerationJob): void
    {
        try {
            event(new AiJobStatusUpdated($aiGenerationJob));
        } catch (Throwable $e) {
            Log::warning("WebSocket broadcast failed for AiGenerationJob {$aiGenerationJob->id} (Reverb server down or unreachable): ".$e->getMessage());
        }
    }
}
