<?php

namespace Modules\Page\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Page\Models\Page;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Modules\Page\Actions\UpdatePageContentAction;
use Modules\Page\Http\Requests\UpdatePageContentRequest;

class PageStatusController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function publish(Page $page)
    {
        Gate::authorize('update_page', $page);

        Cache::forget('frontpage');

        $page->togglePublish();

        return Redirect::back()->with('success', 'Status changed!');
    }

    public function saveAndpublish(UpdatePageContentRequest $request, Page $page)
    {
        Gate::authorize('update_page', $page);

        app(UpdatePageContentAction::class)->handle($request, $page);

        Cache::forget('frontpage');
        
        $page->refresh()->publish();

        return Redirect::back()->with('success', 'Page published!');
    }
}
