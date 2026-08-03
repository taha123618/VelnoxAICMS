<?php

declare(strict_types=1);

namespace Modules\Media\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;
// use Modules\Media\Database\Factories\MediaFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class Media extends BaseMedia
{
    use SoftDeletes;

    public function getDisplayName(): string
    {
        return $this->name.'.'.$this->extension;
    }

    public function getIcon(): string
    {
        if (File::exists(public_path("assets/images/{$this->type}.png"))) {
            return "/assets/images/{$this->type}.png";
        }

        return '/assets/images/other.png';
    }
}
