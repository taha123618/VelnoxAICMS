<?php

namespace Modules\Page\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Modules\Page\Actions\UpdatePageContentAction;
use Modules\Page\Events\PageContentUpdated;
use Modules\Page\Http\Requests\UpdatePageContentRequest;
use Modules\Page\Models\Page;

class PageContentController extends Controller
{
    public function __invoke(UpdatePageContentRequest $request, Page $page)
    {
        Gate::authorize('update_page', $page);

        Cache::forget('frontpage');

        app(UpdatePageContentAction::class)->handle($request, $page);

        broadcast(new PageContentUpdated($page->id, $request->input('content', []), auth()->id()))->toOthers();

        return Redirect::back()->with('success', 'Page updated!');
    }
}
