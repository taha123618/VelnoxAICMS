<?php

declare(strict_types=1);

namespace Modules\Page\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Modules\Page\Actions\MarkPageAsFrontPageAction;
use Modules\Page\Models\Page;

class FrontpageController extends Controller
{
    public function __invoke(Request $request, Page $page)
    {
        Gate::authorize('update_page', $page);

        Cache::forget('frontpage');

        resolve(MarkPageAsFrontPageAction::class)->handle($page);

        return back()->with('success', 'Frontpage changed!');

    }
}
