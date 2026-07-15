<?php

namespace Modules\Page\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Page\Models\Page;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Modules\Page\Actions\UpdatePostContentAction;
use Modules\Page\Http\Requests\UpdatePostContentRequest;

class PostContentController extends Controller
{
    public function __invoke(UpdatePostContentRequest $request, Page $post)
    {

        Gate::authorize('update_post', $post);
        
        app(UpdatePostContentAction::class)->handle($request, $post);
        
        return Redirect::back()->with('success', 'Post updated!');
    }
}
