<?php

declare(strict_types=1);

namespace Modules\Page\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Page\Actions\DuplicatePageAction;
use Modules\Page\Models\Page;

class PageDuplicationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Page $page)
    {

        resolve(DuplicatePageAction::class)->handle($page);

        return back()->with('success', 'Entry has been duplicated successfully');

    }
}
