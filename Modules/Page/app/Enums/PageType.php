<?php

declare(strict_types=1);

namespace Modules\Page\Enums;

enum PageType: string
{
    case Page = 'page';

    case Post = 'post';
}
