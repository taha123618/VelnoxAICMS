<?php

namespace Modules\Layout\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LayoutContentUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $layoutId;

    public array $content;

    public string $userId;

    /**
     * Create a new event instance.
     */
    public function __construct(string $layoutId, array $content, string $userId)
    {
        $this->layoutId = $layoutId;
        $this->content = $content;
        $this->userId = $userId;
    }

    /**
     * Get the channels the event should be broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('layout.'.$this->layoutId),
        ];
    }
}
