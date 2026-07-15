<?php

namespace Modules\Contacts\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;

class Contact extends BaseModel
{

    protected $casts = [
        'subscribe_to_mail' => 'boolean'
    ];


    public function scopeFilter(Builder $query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->whereAny(['name', 'email'], 'like', "%$search%");
        });
        
    }
}
