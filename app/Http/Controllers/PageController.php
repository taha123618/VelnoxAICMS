<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Modules\Page\Models\Page;
use Modules\Menu\Data\MenuData;
use Modules\Page\Data\PageData;
use Modules\Page\Data\PostData;
use Modules\Page\Enums\PageType;
use Modules\Layout\Data\LayoutData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;
use Modules\Menu\Actions\GetAllMenusAction;

class PageController extends Controller
{
    public function index()
    {

        $page = Cache::rememberForever('frontpage', function () {
            return Page::query()->frontpage()->with('layout')->first();
        });

        if ($page) {
            visitor()->visit($page);
            return Inertia::render('page', [
                'page' => PageData::fromModel($page->getPublishedModel()),
                'layout' => LayoutData::fromModel($page->layout),
                'menus' =>  MenuData::collect(app(GetAllMenusAction::class)->handle())
            ]);
        }
        return Inertia::render('index');
    }


    public function show(Page $page)
    {

        abort_if(!$page->is_published, 404);

        $page = $page->getPublishedModel();

         visitor()->visit($page);

        return Inertia::render('page', [
            'page' => $page->type == PageType::Post ? PostData::fromModel($page) : PageData::fromModel($page),
            'layout' => LayoutData::fromModel($page->layout),
            'menus' =>  MenuData::collect(app(GetAllMenusAction::class)->handle())
        ]);
    }
}
