<?php

namespace Modules\Page\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Page\Models\Page;
use App\Http\Controllers\Controller;
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
