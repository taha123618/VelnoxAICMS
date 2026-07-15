<?php

namespace Modules\Category\Actions;

use Modules\Category\Models\Category;
use Modules\Category\Http\Requests\UpdateCategoryRequest;

class UpdateCategoryAction
{
    public function handle(UpdateCategoryRequest $request, Category $category)
    {

        return  tap($category)->update([
            'name' => $request->name,
            'description' => $request->description,
            'parent_id' => $request->parent
        ]);
        
    }
}
