<?php

declare(strict_types=1);

namespace Modules\Localization\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'code',
    'name',
    'native_name',
    'is_default',
    'is_active',
])]
class Language extends Model
{
    #[\Override]
    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];
}
