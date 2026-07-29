<?php

declare(strict_types=1);

namespace Modules\Ai\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Modules\Ai\Models\AiGenerationJob;

class AiJobCompletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public AiGenerationJob $job) {}

    /**
     * Get notification delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get database representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'job_id' => $this->job->id,
            'type' => $this->job->type,
            'status' => $this->job->status,
            'prompt' => $this->job->prompt,
            'error' => $this->job->error,
            'completed_at' => now()->toIso8601String(),
        ];
    }
}
