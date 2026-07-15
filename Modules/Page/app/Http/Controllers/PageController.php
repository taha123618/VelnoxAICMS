<?php

namespace Modules\Page\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Modules\Page\Models\Page;
use Modules\Menu\Data\MenuData;
use Modules\Page\Data\PageData;
use Modules\Layout\Data\LayoutData;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Modules\Page\Actions\CreatePageAction;
use Modules\Page\Actions\DeletePageAction;
use Modules\Menu\Actions\GetAllMenusAction;
use Modules\Page\Actions\SearchPagesAction;
use Modules\Page\Http\Requests\CreatePageRequest;
use Modules\Layout\Actions\GetLayoutDropdownOptionsAction;

class PageController extends Controller
{

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'sort']);

        $data = app(SearchPagesAction::class)->handle($request);

        return Inertia::render('Page::pages/index', [
            'data' => PageData::collect($data),
            'filters' => $filters
        ]);
    }

    public function create()
    {
        Gate::denyIf(Auth::user()->cannot('create_pages'));

        return Inertia::render('Page::pages/create', [
            'layouts' => app(GetLayoutDropdownOptionsAction::class)->handle()
        ]);
    }

    public function store(CreatePageRequest $request)
    {
        Gate::denyIf(Auth::user()->cannot('create_pages'));
        
        app(CreatePageAction::class)->handle($request);

        return Redirect::back()->with('success', 'Page created!');;
    }

    public function edit(Page $page)
    {
        Gate::authorize('update_page', $page);

        return Inertia::render('Page::pages/edit', [
            'page' => PageData::fromModel($page),
            'layouts' => app(GetLayoutDropdownOptionsAction::class)->handle(),
            'layout' => LayoutData::fromModel($page->layout),
            'menus' => MenuData::collect(app(GetAllMenusAction::class)->handle())
        ]);
    }


    public function destroy(Page $page)
    {
        Gate::authorize('delete_page', $page);

        app(DeletePageAction::class)->handle($page);

        return Redirect::back()->with('success', 'Page deleted!');
    }
}
