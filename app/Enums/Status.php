<?php

namespace App\Enums;

use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript()]
enum Status: string
{
    case Published = 'Published';
    case Draft = 'Draft';

    public function getColor()
    {
        return match ($this) {
            self::Published => 'success',
            self::Draft => 'neutral'
        };
    }
}
