<?php

namespace Modules\Page\Http\Controllers;

use Inertia\Inertia;
use App\Enums\Status;
use Illuminate\Http\Request;
use Modules\Page\Models\Page;
use Modules\Page\Data\PageData;
use Modules\Layout\Models\Layout;
use Modules\Layout\Data\LayoutData;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Modules\Layout\Actions\GetAllLayoutsAction;
use Modules\Page\Http\Requests\UpdatePageRequest;
use Modules\Page\Actions\UpdatePageMetadataAction;

class PageMetaController extends Controller
{

    public function edit(Page $page)
    {
        Gate::authorize('update_page', $page);
        
        return Inertia::render('Page::pages/edit-metadata', [
            'page' => PageData::fromModel($page),
            'layouts' => LayoutData::collect(app(GetAllLayoutsAction::class)->handle()),
        ]);
    }

    public function update(UpdatePageRequest $request, Page $page)
    {
        Gate::authorize('update_page', $page);
        
        app(UpdatePageMetadataAction::class)->handle($request, $page);

        return Redirect::back()->with('success', 'Page updated!');
    }
}
