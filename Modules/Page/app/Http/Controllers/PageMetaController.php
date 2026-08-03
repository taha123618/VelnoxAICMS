<?php

namespace Modules\Page\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Modules\Layout\Actions\GetAllLayoutsAction;
use Modules\Layout\Data\LayoutData;
use Modules\Page\Actions\UpdatePageMetadataAction;
use Modules\Page\Data\PageData;
use Modules\Page\Http\Requests\UpdatePageRequest;
use Modules\Page\Models\Page;

class PageMetaController extends Controller
{
    public function edit(Page $page)
    {
        Gate::authorize('update_page', $page);

        return Inertia::render('Page::pages/edit-metadata', [
            'page' => PageData::fromModel($page),
            'layouts' => LayoutData::collect(resolve(GetAllLayoutsAction::class)->handle()),
        ]);
    }

    public function update(UpdatePageRequest $updatePageRequest, Page $page)
    {
        Gate::authorize('update_page', $page);

        resolve(UpdatePageMetadataAction::class)->handle($updatePageRequest, $page);

        return back()->with('success', 'Page updated!');
    }
}
