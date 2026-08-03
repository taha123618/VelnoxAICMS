<?php

namespace Modules\Menu\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Modules\Menu\Actions\CreateMenuAction;
use Modules\Menu\Actions\DeleteMenuAction;
use Modules\Menu\Actions\SearchMenusAction;
use Modules\Menu\Actions\UpdateMenuAction;
use Modules\Menu\Data\MenuData;
use Modules\Menu\Http\Requests\CreateMenuRequest;
use Modules\Menu\Http\Requests\UpdateMenuRequest;
use Modules\Menu\Models\Menu;
use Modules\Page\Actions\GetAllPagesAction;
use Modules\Page\Actions\GetAllPostsAction;
use Modules\Page\Data\PageData;
use Modules\Page\Data\PostData;

class MenuController extends Controller
{
    public function index(Request $request)
    {

        $data = resolve(SearchMenusAction::class)->handle($request);

        $filters = $request->only(['search', 'sort']);

        return Inertia::render('Menu::index', [
            'data' => MenuData::collect($data),
            'filters' => $filters,
        ]);
    }

    public function create()
    {
        Gate::authorize('create', Menu::class);

        return Inertia::render('Menu::create');
    }

    public function store(CreateMenuRequest $createMenuRequest)
    {
        Gate::authorize('create', Menu::class);

        resolve(CreateMenuAction::class)->handle($createMenuRequest);

        return back()->with('success', 'Menu created!');
    }

    public function show(Menu $menu): MenuData
    {
        return MenuData::fromModel($menu->load('items.children'));
    }

    public function edit(Menu $menu)
    {
        Gate::authorize('update', $menu);

        return Inertia::render('Menu::edit', [
            'menu' => MenuData::fromModel($menu->load('items', 'items.children')),
            'pages' => PageData::collect(resolve(GetAllPagesAction::class)->handle()),
            'posts' => PostData::collect(resolve(GetAllPostsAction::class)->handle()),
        ]);
    }

    public function update(UpdateMenuRequest $updateMenuRequest, Menu $menu)
    {

        Gate::authorize('update', $menu);

        resolve(UpdateMenuAction::class)->handle($updateMenuRequest, $menu);

        return back()->with('success', 'Menu updated!');
    }

    public function destroy(Menu $menu)
    {
        Gate::authorize('delete', $menu);

        resolve(DeleteMenuAction::class)->handle($menu);

        return back()->with('success', 'Menu deleted!');
    }
}
