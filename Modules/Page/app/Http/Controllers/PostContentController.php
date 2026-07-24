<?php

namespace Modules\Page\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Modules\Page\Actions\UpdatePostContentAction;
use Modules\Page\Events\PageContentUpdated;
use Modules\Page\Http\Requests\UpdatePostContentRequest;
use Modules\Page\Models\Page;

class PostContentController extends Controller
{
    public function __invoke(UpdatePostContentRequest $updatePostContentRequest, Page $page)
    {

        Gate::authorize('update_post', $page);

        resolve(UpdatePostContentAction::class)->handle($updatePostContentRequest, $page);

        broadcast(new PageContentUpdated($page->id, $updatePostContentRequest->input('content', []), auth()->id()))->toOthers();

        return back()->with('success', 'Post updated!');
    }
}
