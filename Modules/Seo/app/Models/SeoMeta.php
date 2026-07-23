<?php

namespace Modules\Seo\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SeoMeta extends Model
{
    protected $fillable = [
        'title',
        'description',
        'keywords',
        'canonical_url',
        'og_image',
        'robots',
    ];

    public function seoable(): MorphTo
    {
        return $this->morphTo();
    }
}
