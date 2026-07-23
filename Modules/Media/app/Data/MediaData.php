<?php

namespace Modules\Media\Data;

use Illuminate\Support\Number;
use Modules\Media\Models\Media;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript()]
class MediaData extends Data
{
    public function __construct(
        public string $id,
        public string $uuid,
        public string $name,
        public string $size,
        public string $updatedAt,
        public string|Optional $url,
        public string|Optional $thumbnail
    ) {}

    public static function fromModel(Media|\Spatie\MediaLibrary\MediaCollections\Models\Media $media): self
    {
        return new self(
            id: $media->id,
            uuid: $media->uuid,
            name: $media->file_name,
            size: Number::fileSize($media->size),
            updatedAt: $media->updated_at->format('F d, Y'),
            url: $media->getFullUrl(),
            thumbnail: $media->hasGeneratedConversion('thumbnail')
                ? $media->getFullUrl('thumbnail')
                : $media->getFullUrl()
        );
    }
}
