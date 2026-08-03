<?php

namespace Modules\Testimonial\Models;

use App\Enums\Status;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Gate;
use Modules\Testimonial\Policies\TestimonialPolicy;

#[UsePolicy(TestimonialPolicy::class)]
class Testimonial extends BaseModel
{
    #[\Override]
    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected function isPublished(): Attribute
    {
        return Attribute::make(
            get: fn (): bool => filled($this->published_at),
            set: fn ($value): array => ['published_at' => $value ? now() : null]
        );
    }

    public function togglePublish(): void
    {
        $this->is_published ? $this->unpublish() : $this->publish();
    }

    public function publish(): void
    {
        $this->update(['published_at' => now()]);
    }

    public function unpublish(): void
    {
        $this->update(['published_at' => null]);
    }

    public function getStatus(): Status
    {
        return is_null($this->published_at)
            ? Status::Draft
            : Status::Published;
    }

    protected function scopePublished(Builder $builder): void
    {
        $builder->whereNotNull('published_at');
    }

    protected function scopeFilter(Builder $builder, array $filters): void
    {
        $builder->when($filters['search'] ?? null, function ($query, $search): void {
            $query->where('name', 'like', "%$search%")
                ->orWhere('title', 'like', "%$search%");
        })->when($filters['trashed'] ?? null, function ($query, $trashed): void {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }

    protected function getAuthorizationAttribute(): array
    {
        return [
            'update' => Gate::allows('update', $this),
            'delete' => Gate::allows('delete', $this),
        ];
    }
}
