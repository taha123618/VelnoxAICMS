<?php

namespace Modules\Content\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'collection_id',
    'name',
    'handle',
    'type',
    'validation_rules',
    'ui_config',
    'order_column',
])]
class Field extends BaseModel
{
    #[\Override]
    protected function casts(): array
    {
        return [
            'validation_rules' => 'array',
            'ui_config' => 'array',
            'order_column' => 'integer',
        ];
    }

    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }
}
