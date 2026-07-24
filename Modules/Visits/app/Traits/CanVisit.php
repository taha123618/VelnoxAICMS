<?php

namespace Modules\Visits\Traits;

use Modules\Visits\Models\Visit;

trait CanVisit
{
    public function visitLogs()
    {
        return $this->morphMany(Visit::class, 'visitor');
    }

    protected function scopeOnline($query, $seconds = 180)
    {
        $time = now()->subSeconds($seconds);

        return $query->whereHas('visitLogs', function ($query) use ($time): void {
            $query->where('visits.created_at', '>=', $time->toDateTime());
        });
    }

    public function isOnline($seconds = 180): bool
    {
        $time = now()->subSeconds($seconds);

        return $this->visitLogs()->whereHasMorph('user', [static::class], function ($query) use ($time): void {
            $query
                ->where('user_id', $this->id)
                ->where('visits.created_at', '>=', $time->toDateTime());
        })->count() > 0;
    }
}
