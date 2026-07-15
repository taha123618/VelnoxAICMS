<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Modules\Page\Models\Page;
use Modules\Menu\Data\MenuData;
use Modules\Page\Data\PageData;
use Modules\Page\Data\PostData;
use Modules\Page\Enums\PageType;
use Modules\Layout\Data\LayoutData;
use Modules\Menu\Actions\GetAllMenusAction;

class PreviewPageController extends Controller
{
    
    public function __invoke(Page $page)
    {
        return Inertia::render('page', [
            'page' => $page->type == PageType::Post ? PostData::fromModel($page) : PageData::fromModel($page),
            'layout' => LayoutData::fromModel($page->layout),
            'menus' =>  MenuData::collect(app(GetAllMenusAction::class)->handle())
        ]);
    }
}
