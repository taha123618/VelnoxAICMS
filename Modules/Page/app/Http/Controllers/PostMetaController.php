<?php

namespace Modules\Page\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Modules\Category\Actions\GetCategoryDropdownOptionsAction;
use Modules\Layout\Actions\GetAllLayoutsAction;
use Modules\Layout\Data\LayoutData;
use Modules\Page\Actions\UpdatePostMetadataAction;
use Modules\Page\Data\PostData;
use Modules\Page\Http\Requests\UpdatePostRequest;
use Modules\Page\Models\Page;

class PostMetaController extends Controller
{
    public function edit(Page $post)
    {
        Gate::authorize('update_post', $post);

        $categories = app(GetCategoryDropdownOptionsAction::class)->handle();

        return Inertia::render('Page::posts/edit-metadata', [
            'post' => PostData::fromModel($post),
            'layouts' => LayoutData::collect(app(GetAllLayoutsAction::class)->handle()),
            'categories' => $categories,
        ]);
    }

    public function update(UpdatePostRequest $request, Page $post)
    {
        Gate::authorize('update_post', $post);

        app(UpdatePostMetadataAction::class)->handle($request, $post);

        return Redirect::back()->with('success', 'Post updated!');
    }
}
