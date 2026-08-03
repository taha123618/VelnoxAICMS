<?php

namespace Modules\Layout\Models;

use App\Enums\Status;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Modules\Layout\Policies\LayoutPolicy;
use Modules\Page\Models\Page;

#[UsePolicy(LayoutPolicy::class)]
class Layout extends BaseModel
{
    #[\Override]
    protected $casts = [
        'published_at' => 'datetime',
        'content' => 'json',
    ];

    public function pages()
    {
        return $this->hasMany(Page::class);
    }

    public function getStatus(): Status
    {
        return is_null($this->published_at)
            ? Status::Draft
            : Status::Published;
    }

    protected function scopeFilter(Builder $builder, array $filters): void
    {
        $builder->when($filters['search'] ?? null, function ($query, $search): void {
            $query->where('name', 'like', "%$search%");
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
