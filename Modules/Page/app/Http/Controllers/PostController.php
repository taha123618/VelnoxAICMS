<?php

namespace Modules\Page\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Modules\Page\Models\Page;
use Modules\Menu\Data\MenuData;
use Modules\Page\Data\PostData;
use Modules\Layout\Data\LayoutData;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Modules\Category\Actions\GetCategoryDropdownOptionsAction;
use Modules\Layout\Actions\GetLayoutDropdownOptionsAction;
use Modules\Menu\Actions\GetAllMenusAction;
use Modules\Page\Actions\CreatePostAction;
use Modules\Page\Actions\DeletePostAction;
use Modules\Page\Actions\SearchPostsAction;
use Modules\Page\Http\Requests\CreatePostRequest;

class PostController extends Controller
{
    public function index(Request $request)
    {
        
        $filters = $request->only(['search', 'sort']);

        $data = app(SearchPostsAction::class)->handle($request);

        return Inertia::render('Page::posts/index', [
            'data' => PostData::collect($data),
            'filters' => $filters
        ]);
    }

    public function create()
    {
        Gate::denyIf(Auth::user()->cannot('create_posts'));

        $layouts = app(GetLayoutDropdownOptionsAction::class)->handle();

        $categories = app(GetCategoryDropdownOptionsAction::class)->handle();

        return Inertia::render('Page::posts/create', [
            'layouts' => $layouts,
            'categories' => $categories
        ]);
    }

    public function store(CreatePostRequest $request)
    {

        Gate::denyIf(Auth::user()->cannot('create_posts'));

        app(CreatePostAction::class)->handle($request);

        return Redirect::back()->with('success', 'Post created!');

    }

    public function edit(Page $post)
    {
        Gate::authorize('update_post', $post);

        return Inertia::render('Page::posts/edit', [
            'layouts' => app(GetLayoutDropdownOptionsAction::class)->handle(),
            'post' => PostData::fromModel($post),
            'layout' => LayoutData::fromModel($post->layout),
            'menus' => MenuData::collect(app(GetAllMenusAction::class)->handle())
        ]);
    }

    public function destroy(Page $post)
    {

        Gate::authorize('delete_post', $post);

        app(DeletePostAction::class)->handle($post);

        return Redirect::back()->with('success', 'Post deleted!');
    }
}
