<?php

namespace Modules\Page\Http\Controllers;

use Modules\Page\Models\Page;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Modules\Page\Actions\UpdatePageContentAction;
use Modules\Page\Http\Requests\UpdatePageContentRequest;

class PageContentController extends Controller
{

    public function __invoke(UpdatePageContentRequest $request, Page $page)
    {
        Gate::authorize('update_page', $page);

        Cache::forget('frontpage');
        
        app(UpdatePageContentAction::class)->handle($request, $page);

        return Redirect::back()->with('success', 'Page updated!');
    }
}
