<?php

namespace Modules\Page\Http\Controllers;

use Inertia\Inertia;
use Modules\Page\Models\Page;
use Modules\Page\Data\PostData;
use Modules\Layout\Data\LayoutData;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Modules\Layout\Actions\GetAllLayoutsAction;
use Modules\Page\Http\Requests\UpdatePostRequest;
use Modules\Page\Actions\UpdatePostMetadataAction;
use Modules\Category\Actions\GetCategoryDropdownOptionsAction;

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
