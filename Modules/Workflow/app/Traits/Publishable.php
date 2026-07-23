<?php

namespace Modules\Workflow\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Publishable
{
    /**
     * Scope a query to only include published models.
     */
    public function scopePublished(Builder $query)
    {
        return $query->where('status', 'published')
                     ->where('published_at', '<=', now());
    }

    /**
     * Scope a query to only include drafted models.
     */
    public function scopeDrafted(Builder $query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope a query to only include models in review.
     */
    public function scopeInReview(Builder $query)
    {
        return $query->where('status', 'review');
    }

    /**
     * Check if the model is published.
     */
    public function isPublished(): bool
    {
        return $this->status === 'published' && $this->published_at && $this->published_at->isPast();
    }

    /**
     * Check if the model is scheduled.
     */
    public function isScheduled(): bool
    {
        return $this->status === 'published' && $this->published_at && $this->published_at->isFuture();
    }

    /**
     * Publish the model.
     */
    public function publish($publishedAt = null): void
    {
        $this->status = 'published';
        $this->published_at = $publishedAt ?? now();
        $this->save();
    }

    /**
     * Revert the model to draft.
     */
    public function draft(): void
    {
        $this->status = 'draft';
        $this->save();
    }
}
