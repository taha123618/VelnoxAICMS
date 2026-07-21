<?php

namespace Modules\Ai\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Ai\Models\AiGenerationJob;

class AiJobStatusUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $jobId;

    public ?string $userId;

    public string $type;

    public string $status;

    public int $progress;

    public ?array $result;

    public ?string $error;

    public string $prompt;

    public function __construct(AiGenerationJob $job)
    {
        $this->jobId = $job->id;
        $this->userId = $job->user_id;
        $this->type = $job->type;
        $this->status = $job->status;
        $this->progress = $job->progress;
        $this->result = $job->result;
        $this->error = $job->error;
        $this->prompt = $job->prompt;
    }

    public function broadcastOn(): array
    {
        $channels = [
            new PrivateChannel("ai-job.{$this->jobId}"),
        ];

        if ($this->userId) {
            $channels[] = new PrivateChannel("user.{$this->userId}");
        }

        return $channels;
    }

    public function broadcastAs(): string
    {
        return 'AiJobStatusUpdated';
    }
}
