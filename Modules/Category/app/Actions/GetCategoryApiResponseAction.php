<?php

namespace Modules\Category\Actions;

use Illuminate\Http\Request;
use Modules\Category\Models\Category;

class GetCategoryApiResponseAction
{
    public function handle(Request $request)
    {
        $builder = Category::query();

        if ($request->orderBy == 'random') {
            $builder->inRandomOrder();
        } else {
            $builder->orderBy($request->orderBy, $request->orderDir);
        }

        return $builder->limit($request->perPage)->get();
    }
}
