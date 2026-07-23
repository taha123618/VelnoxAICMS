<?php

namespace Modules\Category\Actions;

use Illuminate\Http\Request;
use Modules\Category\Models\Category;

class CreateCategoryAction
{
    public function handle(Request $request)
    {
        return Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'parent_id' => $request->parent,
        ]);
    }
}
