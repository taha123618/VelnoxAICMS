<?php

namespace Modules\Category\Actions;

use Illuminate\Http\Request;
use Modules\Category\Models\Category;

class GetCategoryApiResponseAction
{
    public function handle(Request $request)
    {
        $categories = Category::query();

        if ($request->orderBy == 'random') {
            $categories->inRandomOrder();
        } else {
            $categories->orderBy($request->orderBy, $request->orderDir);
        }

        return $categories->limit($request->perPage)->get();
    }
}
