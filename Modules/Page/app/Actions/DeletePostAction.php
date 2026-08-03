<?php

declare(strict_types=1);

namespace Modules\Page\Actions;

use Modules\Page\Models\Page;

class DeletePostAction
{
    public function handle(Page $page): void
    {
        $page->delete();
    }
}
