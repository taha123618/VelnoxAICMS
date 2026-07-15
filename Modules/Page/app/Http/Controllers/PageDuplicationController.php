<?php

namespace Modules\Page\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Modules\Page\Actions\DuplicatePageAction;
use Modules\Page\Models\Page;

class PageDuplicationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Page $page)
    {

        app(DuplicatePageAction::class)->handle($page);

        return Redirect::back()->with('success', 'Entry has been duplicated successfully');
        
    }
}
