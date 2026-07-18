<?php

namespace Modules\Page\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Modules\Page\Actions\UpdatePostContentAction;
use Modules\Page\Events\PageContentUpdated;
use Modules\Page\Http\Requests\UpdatePostContentRequest;
use Modules\Page\Models\Page;

class PostContentController extends Controller
{
    public function __invoke(UpdatePostContentRequest $request, Page $post)
    {

        Gate::authorize('update_post', $post);

        app(UpdatePostContentAction::class)->handle($request, $post);

        broadcast(new PageContentUpdated($post->id, $request->input('content', []), auth()->id()))->toOthers();

        return Redirect::back()->with('success', 'Post updated!');
    }
}
