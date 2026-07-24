<?php

declare(strict_types=1);

namespace App\Enums;

use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript()]
enum Status: string
{
    case Published = 'Published';
    case Draft = 'Draft';

    public function getColor(): string
    {
        return match ($this) {
            self::Published => 'success',
            self::Draft => 'neutral'
        };
    }
}
