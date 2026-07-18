<?php

namespace Modules\Content\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Field extends BaseModel
{
    protected $fillable = [
        'collection_id',
        'name',
        'handle',
        'type',
        'validation_rules',
        'ui_config',
        'order_column',
    ];

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
