<?php

namespace Modules\Page\Actions;

use Illuminate\Http\Request;
use Modules\Page\Models\Page;

class GetPostApiResponseAction
{
    public function handle(Request $request)
    {
        $posts = Page::query()
            ->published()
            ->posts();

        if ($request->fromCategories == 1 && $request->categories &&  count($request->categories) > 0) {
            $posts->whereIn('category_id', $request->categories);
        }

        if ($request->orderBy == 'random') {
            $posts->inRandomOrder();
        } else {
            $posts->orderBy($request->orderBy, $request->orderDir);
        }

        return $posts->limit($request->perPage)->get();
    }
}
