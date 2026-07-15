<?php

namespace Modules\Category\Actions;

use Modules\Category\Models\Category;

class DeleteCategoryAction
{
    public function handle(Category $category)
    {

        $category->delete();
    }
}
