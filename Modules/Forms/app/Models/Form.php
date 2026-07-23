<?php

namespace Modules\Forms\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'slug',
    'description',
    'schema',
    'is_active',
    'success_message',
    'redirect_url',
])]
class Form extends Model
{
    use HasFactory;

    #[\Override]
    protected $casts = [
        'schema' => 'array',
        'is_active' => 'boolean',
    ];

    public function submissions(): HasMany
    {
        return $this->hasMany(FormSubmission::class);
    }
}
