<?php

namespace Modules\Ai\Events;

use Illuminate\Broadcasting\Channel;
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

    public function __construct(AiGenerationJob $aiGenerationJob)
    {
        $this->jobId = $aiGenerationJob->id;
        $this->userId = $aiGenerationJob->user_id;
        $this->type = $aiGenerationJob->type;
        $this->status = $aiGenerationJob->status;
        $this->progress = $aiGenerationJob->progress;
        $this->result = $aiGenerationJob->result;
        $this->error = $aiGenerationJob->error;
        $this->prompt = $aiGenerationJob->prompt;
    }

    public function broadcastOn(): array
    {
        $channels = [
            new Channel("ai-jobs.{$this->jobId}"),
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
