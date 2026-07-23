<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tenant extends BaseModel
{
    protected $fillable = [
        'name',
        'slug',
        'domain',
        'logo_url',
        'is_active',
        'settings',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'settings' => 'array',
        ];
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(\Modules\Auth\Models\User::class)->withTimestamps();
    }
}
