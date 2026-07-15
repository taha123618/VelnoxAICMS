<?php

namespace Modules\Category\Actions;

use Modules\Category\Models\Category;

class GetCategoryDropdownOptionsAction
{
    public function handle()
    {
        return Category::query()->get()->select('id', 'name');
    }
}
