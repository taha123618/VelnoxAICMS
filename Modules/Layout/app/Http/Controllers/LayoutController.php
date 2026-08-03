<?php

namespace Modules\Layout\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Modules\Layout\Actions\CreateLayoutAction;
use Modules\Layout\Actions\DeleteLayoutAction;
use Modules\Layout\Actions\SearchLayoutsAction;
use Modules\Layout\Actions\UpdateLayoutAction;
use Modules\Layout\Data\LayoutData;
use Modules\Layout\Events\LayoutContentUpdated;
use Modules\Layout\Http\Requests\CreateLayoutRequest;
use Modules\Layout\Http\Requests\UpdateLayoutRequest;
use Modules\Layout\Models\Layout;
use Modules\Menu\Actions\GetAllMenusAction;
use Modules\Menu\Data\MenuData;

class LayoutController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'sort']);

        $data = resolve(SearchLayoutsAction::class)->handle($request);

        return Inertia::render('Layout::index', [
            'data' => LayoutData::collect($data),
            'filters' => $filters,
        ]);
    }

    public function create()
    {
        Gate::authorize('create', Layout::class);

        return Inertia::render('Layout::create');
    }

    public function store(CreateLayoutRequest $createLayoutRequest)
    {

        Gate::authorize('create', Layout::class);

        Cache::forget('frontpage');

        resolve(CreateLayoutAction::class)->handle($createLayoutRequest);

        return back()->with('success', 'Layout created!');
    }

    public function edit(Layout $layout)
    {
        Gate::authorize('update', $layout);

        return Inertia::render('Layout::edit', [
            'layout' => LayoutData::fromModel($layout),
            'menus' => MenuData::collect(resolve(GetAllMenusAction::class)->handle()),
        ]);
    }

    public function update(UpdateLayoutRequest $updateLayoutRequest, Layout $layout)
    {
        Gate::authorize('update', $layout);

        Cache::forget('frontpage');

        resolve(UpdateLayoutAction::class)->handle($updateLayoutRequest, $layout);

        broadcast(new LayoutContentUpdated($layout->id, $updateLayoutRequest->input('content', []), auth()->id()))->toOthers();

        return back()->with('success', 'Layout updated!');
    }

    public function destroy(Layout $layout)
    {

        Gate::authorize('delete', $layout);

        resolve(DeleteLayoutAction::class)->handle($layout);

        return back()->with('success', 'Category deleted!');
    }
}
