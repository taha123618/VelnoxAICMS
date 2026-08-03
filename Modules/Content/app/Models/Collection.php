<?php

namespace Modules\Content\Models;

use App\Models\BaseModel;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'tenant_id',
    'name',
    'slug',
    'description',
    'is_publishable',
])]
class Collection extends BaseModel
{
    use BelongsToTenant;

    #[\Override]
    protected function casts(): array
    {
        return [
            'is_publishable' => 'boolean',
        ];
    }

    public function fields(): HasMany
    {
        return $this->hasMany(Field::class)->orderBy('order_column');
    }

    public function entries(): HasMany
    {
        return $this->hasMany(Entry::class);
    }
}
