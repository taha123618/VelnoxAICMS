<?php

namespace Modules\Page\Actions;

use Modules\Page\Models\Page;

class DeletePageAction
{
    public function handle(Page $page)
    {
        $page->delete();
    }
}
