<?php

namespace Modules\Menu\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Modules\Menu\Models\Menu;
use Modules\Menu\Data\MenuData;
use Modules\Page\Data\PageData;
use Modules\Page\Data\PostData;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Modules\Layout\Models\Layout;
use Modules\Menu\Actions\CreateMenuAction;
use Modules\Menu\Actions\DeleteMenuAction;
use Modules\Menu\Actions\UpdateMenuAction;
use Modules\Menu\Actions\SearchMenusAction;
use Modules\Page\Actions\GetAllPagesAction;
use Modules\Page\Actions\GetAllPostsAction;
use Modules\Menu\Http\Requests\CreateMenuRequest;
use Modules\Menu\Http\Requests\UpdateMenuRequest;

class MenuController extends Controller
{

    public function index(Request $request)
    {

        $data = app(SearchMenusAction::class)->handle($request);

        $filters = $request->only(['search', 'sort']);

        return Inertia::render('Menu::index', [
            'data' => MenuData::collect($data),
            'filters' => $filters
        ]);
    }


    public function create()
    {
        Gate::authorize('create', Menu::class);

        return Inertia::render('Menu::create');
    }


    public function store(CreateMenuRequest $request)
    {
        Gate::authorize('create', Menu::class);

        app(CreateMenuAction::class)->handle($request);

        return Redirect::back()->with('success', 'Menu created!');
    }

    public function show(Menu $menu)
    {
        return MenuData::fromModel($menu->load('items.children'));
    }

    public function edit(Menu $menu)
    {
        Gate::authorize('update', $menu);

        return Inertia::render('Menu::edit', [
            'menu' => MenuData::fromModel($menu->load('items', 'items.children')),
            'pages' => PageData::collect(app(GetAllPagesAction::class)->handle()),
            'posts' => PostData::collect(app(GetAllPostsAction::class)->handle()),
        ]);
    }



    public function update(UpdateMenuRequest $request, Menu $menu)
    {

        Gate::authorize('update', $menu);

        app(UpdateMenuAction::class)->handle($request, $menu);

        return Redirect::back()->with('success', 'Menu updated!');
    }

    public function destroy(Menu $menu)
    {
        Gate::authorize('delete', $menu);

        app(DeleteMenuAction::class)->handle($menu);

        return Redirect::back()->with('success', 'Menu deleted!');
    }
}
