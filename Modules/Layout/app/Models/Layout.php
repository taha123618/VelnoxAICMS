<?php

namespace Modules\Layout\Models;

use App\Enums\Status;
use App\Models\BaseModel;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Modules\Layout\Policies\LayoutPolicy;
use Modules\Page\Models\Page;

#[UsePolicy(LayoutPolicy::class)]
class Layout extends BaseModel
{

    protected $casts = [
        'published_at' => 'datetime',
        'content' => 'json'
    ];

    public function pages()
    {
        return $this->hasMany(Page::class);
    }

    public function getStatus(): Status
    {
        return !is_null($this->published_at)
            ? Status::Published
            : Status::Draft;
    }

    public function scopeFilter(Builder $query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('name', 'like', "%$search%");
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
