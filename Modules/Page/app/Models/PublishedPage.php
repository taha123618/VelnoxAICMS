<?php

declare(strict_types=1);

namespace Modules\Page\Models;

use App\Models\BaseModel;

class PublishedPage extends BaseModel
{
    #[\Override]
    protected $casts = [
        'content' => 'json',
        'data' => 'json',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
