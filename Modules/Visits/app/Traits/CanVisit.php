<?php

namespace Modules\Visits\Traits;

use Modules\Visits\Models\Visit;

trait CanVisit
{

    public function visitLogs()
    {
        return $this->morphMany(Visit::class, 'visitor');
    }

    public function scopeOnline($query, $seconds = 180)
    {
        $time = now()->subSeconds($seconds);

        return $query->whereHas('visitLogs', function ($query) use ($time) {
            $query->where("visits.created_at", '>=', $time->toDateTime());
        });
    }

    public function isOnline($seconds = 180)
    {
        $time = now()->subSeconds($seconds);

        return $this->visitLogs()->whereHasMorph('user', [static::class], function ($query) use ($time) {
            $query
                ->where('user_id', $this->id)
                ->where("visits.created_at", '>=', $time->toDateTime());
        })->count() > 0;
    }

    
}
