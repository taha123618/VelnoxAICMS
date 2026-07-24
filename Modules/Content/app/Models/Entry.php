<?php

namespace Modules\Content\Models;

use App\Models\BaseModel;
use App\Models\User;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

#[Fillable([
    'tenant_id',
    'collection_id',
    'user_id',
    'title',
    'slug',
    'data',
    'status',
    'published_at',
])]
class Entry extends BaseModel implements HasMedia
{
    use BelongsToTenant, InteractsWithMedia;

    #[\Override]
    protected function casts(): array
    {
        return [
            'data' => 'array',
            'published_at' => 'datetime',
        ];
    }

    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
