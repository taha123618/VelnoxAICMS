<?php

namespace Modules\Page\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Modules\Layout\Actions\GetLayoutDropdownOptionsAction;
use Modules\Layout\Data\LayoutData;
use Modules\Menu\Actions\GetAllMenusAction;
use Modules\Menu\Data\MenuData;
use Modules\Page\Actions\CreatePageAction;
use Modules\Page\Actions\DeletePageAction;
use Modules\Page\Actions\SearchPagesAction;
use Modules\Page\Data\PageData;
use Modules\Page\Http\Requests\CreatePageRequest;
use Modules\Page\Models\Page;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'sort']);

        $data = resolve(SearchPagesAction::class)->handle($request);

        return Inertia::render('Page::pages/index', [
            'data' => PageData::collect($data),
            'filters' => $filters,
        ]);
    }

    public function create()
    {
        Gate::denyIf(Auth::user()->cannot('create_pages'));

        return Inertia::render('Page::pages/create', [
            'layouts' => resolve(GetLayoutDropdownOptionsAction::class)->handle(),
        ]);
    }

    public function store(CreatePageRequest $createPageRequest)
    {
        Gate::denyIf(Auth::user()->cannot('create_pages'));

        resolve(CreatePageAction::class)->handle($createPageRequest);

        return back()->with('success', 'Page created!');
    }

    public function edit(Page $page)
    {
        Gate::authorize('update_page', $page);

        return Inertia::render('Page::pages/edit', [
            'page' => PageData::fromModel($page),
            'layouts' => resolve(GetLayoutDropdownOptionsAction::class)->handle(),
            'layout' => LayoutData::fromModel($page->layout),
            'menus' => MenuData::collect(resolve(GetAllMenusAction::class)->handle()),
        ]);
    }

    public function destroy(Page $page)
    {
        Gate::authorize('delete_page', $page);

        resolve(DeletePageAction::class)->handle($page);

        return back()->with('success', 'Page deleted!');
    }
}
