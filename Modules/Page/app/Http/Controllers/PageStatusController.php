<?php

declare(strict_types=1);

namespace Modules\Page\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Modules\Page\Actions\UpdatePageContentAction;
use Modules\Page\Http\Requests\UpdatePageContentRequest;
use Modules\Page\Models\Page;

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

        return back()->with('success', 'Status changed!');
    }

    public function saveAndpublish(UpdatePageContentRequest $updatePageContentRequest, Page $page)
    {
        Gate::authorize('update_page', $page);

        resolve(UpdatePageContentAction::class)->handle($updatePageContentRequest, $page);

        Cache::forget('frontpage');

        $page->refresh()->publish();

        return back()->with('success', 'Page published!');
    }
}
