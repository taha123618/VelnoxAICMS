<?php

namespace Modules\Category\Actions;

use Modules\Category\Http\Requests\UpdateCategoryRequest;
use Modules\Category\Models\Category;

class UpdateCategoryAction
{
    public function handle(UpdateCategoryRequest $updateCategoryRequest, Category $category)
    {

        return tap($category)->update([
            'name' => $updateCategoryRequest->name,
            'description' => $updateCategoryRequest->description,
            'parent_id' => $updateCategoryRequest->parent,
        ]);

    }
}
