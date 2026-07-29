<?php

declare(strict_types=1);

namespace Modules\Automation\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'url',
    'events',
    'is_active',
    'secret',
])]
class Webhook extends Model
{
    #[\Override]
    protected function casts(): array
    {
        return [
            'events' => 'array',
            'is_active' => 'boolean',
        ];
    }
}
