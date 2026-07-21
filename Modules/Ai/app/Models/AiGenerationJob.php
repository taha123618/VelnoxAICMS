<?php

namespace Modules\Ai\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Auth\Models\User;

class AiGenerationJob extends Model
{
    use HasUlids;

    public const STATUS_QUEUED = 'queued';

    public const STATUS_PROCESSING = 'processing';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_FAILED = 'failed';

    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'user_id',
        'type',
        'prompt',
        'status',
        'progress',
        'result',
        'error',
    ];

    protected function casts(): array
    {
        return [
            'result' => 'array',
            'progress' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function markAsProcessing(int $progress = 10): void
    {
        $this->update([
            'status' => self::STATUS_PROCESSING,
            'progress' => $progress,
        ]);
    }

    public function updateProgress(int $progress): void
    {
        $this->update([
            'progress' => min(100, max(0, $progress)),
        ]);
    }

    public function markAsCompleted(array $result): void
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'progress' => 100,
            'result' => $result,
        ]);
    }

    public function markAsFailed(string $errorMessage): void
    {
        $this->update([
            'status' => self::STATUS_FAILED,
            'error' => $errorMessage,
        ]);
    }
}
