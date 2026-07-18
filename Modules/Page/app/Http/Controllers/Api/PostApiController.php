<?php

namespace Modules\Page\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Page\Actions\GetPostApiResponseAction;
use Modules\Page\Data\PostData;

class PostApiController extends Controller
{
    public function index(Request $request)
    {
        $posts = app(GetPostApiResponseAction::class)->handle($request);

        return PostData::collect($posts);
    }
}
