<?php

namespace Modules\Category\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Category\Actions\GetCategoryApiResponseAction;
use Modules\Category\Models\Category;
use Modules\Category\Data\CategoryData;

class CategoryApiController extends Controller
{
    public function index(Request $request)
    {
        $categories =  app(GetCategoryApiResponseAction::class)->handle($request);

        return CategoryData::collect($categories);
    }
}
