<?php

namespace Modules\Category\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Category\Actions\GetCategoryApiResponseAction;
use Modules\Category\Data\CategoryData;

class CategoryApiController extends Controller
{
    public function index(Request $request)
    {
        $categories = app(GetCategoryApiResponseAction::class)->handle($request);

        return CategoryData::collect($categories);
    }
}
