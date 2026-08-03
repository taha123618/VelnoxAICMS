<?php

namespace Modules\Page\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
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
    public function edit(Page $page)
    {
        Gate::authorize('update_post', $page);

        $categories = resolve(GetCategoryDropdownOptionsAction::class)->handle();

        return Inertia::render('Page::posts/edit-metadata', [
            'post' => PostData::fromModel($page),
            'layouts' => LayoutData::collect(resolve(GetAllLayoutsAction::class)->handle()),
            'categories' => $categories,
        ]);
    }

    public function update(UpdatePostRequest $updatePostRequest, Page $page)
    {
        Gate::authorize('update_post', $page);

        resolve(UpdatePostMetadataAction::class)->handle($updatePostRequest, $page);

        return back()->with('success', 'Post updated!');
    }
}
