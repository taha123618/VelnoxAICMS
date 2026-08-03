<?php

namespace Modules\Media\Data;

use Modules\Media\Models\Folder;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript()]
class FolderData extends Data
{
    public function __construct(
        public int|string $id,
        public string $name,
        public array|Optional $can
    ) {}

    public static function fromModel(Folder $folder): self
    {
        return new self(
            id: $folder->id,
            name: $folder->name,
            can: $folder->authorization,
        );
    }
}
