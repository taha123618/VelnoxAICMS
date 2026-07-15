<?php

namespace Modules\Page\Models;

use App\Models\BaseModel;

class PublishedPage extends BaseModel
{

    protected $casts = [
        'content' => 'json',
        'data' => 'json'
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
    
}
