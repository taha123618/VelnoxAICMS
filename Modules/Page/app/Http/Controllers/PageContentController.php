<?php

namespace Modules\Page\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Modules\Page\Actions\UpdatePageContentAction;
use Modules\Page\Events\PageContentUpdated;
use Modules\Page\Http\Requests\UpdatePageContentRequest;
use Modules\Page\Models\Page;

class PageContentController extends Controller
{
    public function __invoke(UpdatePageContentRequest $updatePageContentRequest, Page $page)
    {
        Gate::authorize('update_page', $page);

        Cache::forget('frontpage');

        resolve(UpdatePageContentAction::class)->handle($updatePageContentRequest, $page);

        broadcast(new PageContentUpdated($page->id, $updatePageContentRequest->input('content', []), auth()->id()))->toOthers();

        return back()->with('success', 'Page updated!');
    }
}
