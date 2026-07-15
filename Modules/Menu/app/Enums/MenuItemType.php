<?php

namespace Modules\Menu\Enums;

use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript()]
enum MenuItemType: string
{
    case Page = 'page';
    case Post = 'post';
    case Category = 'category';
    case Custom = 'custom';
}
