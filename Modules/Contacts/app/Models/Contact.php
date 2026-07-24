<?php

declare(strict_types=1);

namespace Modules\Contacts\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;

class Contact extends BaseModel
{
    #[\Override]
    protected $casts = [
        'subscribe_to_mail' => 'boolean',
    ];

    protected function scopeFilter(Builder $builder, array $filters): void
    {
        $builder->when($filters['search'] ?? null, function ($query, $search): void {
            $query->whereAny(['name', 'email'], 'like', "%$search%");
        });

    }
}
