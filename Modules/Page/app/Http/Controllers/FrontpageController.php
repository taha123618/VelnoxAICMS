<?php

namespace Modules\Page\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Page\Models\Page;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Modules\Page\Actions\MarkPageAsFrontPageAction;

class FrontpageController extends Controller
{
    
    public function __invoke(Request $request, Page $page)
    {
        Gate::authorize('update_page', $page);
        
        Cache::forget('frontpage');

        app(MarkPageAsFrontPageAction::class)->handle($page);

        return Redirect::back()->with('success', 'Frontpage changed!');
        
    }
}
