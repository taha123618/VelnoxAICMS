<?php

namespace Modules\Testimonial\Models;

use App\Enums\Status;
use App\Models\BaseModel;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Modules\Testimonial\Policies\TestimonialPolicy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;

#[UsePolicy(TestimonialPolicy::class)]
class Testimonial extends BaseModel
{

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function isPublished(): Attribute
    {
        return Attribute::make(
            get: fn() => filled($this->published_at),
            set: fn($value) => ['published_at' => $value ? now() : null]
        );
    }

    public function togglePublish()
    {
        $this->is_published ? $this->unpublish() : $this->publish();
    }

    public function publish()
    {
        $this->update(['published_at' => now()]);
    }

    public function unpublish()
    {
        $this->update(['published_at' => null]);
    }

    public function getStatus(): Status
    {
        return !is_null($this->published_at)
            ? Status::Published
            : Status::Draft;
    }

    public function scopePublished(Builder $builder)
    {
        $builder->whereNotNull('published_at');
    }

    public function scopeFilter(Builder $query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('name', 'like', "%$search%")
                ->orWhere('title', 'like', "%$search%");
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }

    public function getAuthorizationAttribute()
    {
        return [
            'update' => Gate::allows('update', $this),
            'delete' => Gate::allows('delete', $this)
        ];
    }
}
