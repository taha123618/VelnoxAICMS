<?php

declare(strict_types=1);

namespace Modules\Category\Actions;

use Modules\Category\Models\Category;

class DeleteCategoryAction
{
    public function handle(Category $category): void
    {

        $category->delete();
    }
}
