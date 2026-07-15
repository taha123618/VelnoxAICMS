<?php

namespace Modules\Page\Actions;

use Modules\Page\Models\Page;

class GetAllPostsAction
{
    public function handle()
    {
        return Page::query()->posts()->get();
    }
}
