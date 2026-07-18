<?php

namespace Modules\Builder\Enums;

use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript()]
enum LinkType: string
{
    case Page = 'page';
    case Post = 'post';
    case External = 'external';
    case Category = 'category';

    // public function getOptions()
    // {
    //     return match ($this) {
    //         self::Published => 'success',
    //         self::Draft => 'neutral'
    //     };
    // }
}
