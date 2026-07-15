<?php

namespace Modules\Dashboard\Data;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript()]
class VisitorData extends Data
{
    public function __construct(
        public string $id
    ){}

    /*
    public static function fromModel(Model $model): self
    {
        return new self(
            id: $model->id
        );
    }
    */
}
