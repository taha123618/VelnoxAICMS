<?php

namespace Modules\Page\Actions;

use Modules\Page\Models\Page;

class GetAllPagesAction
{
    public function handle()
    {

        return Page::query()->pages()->get();
    }
}
