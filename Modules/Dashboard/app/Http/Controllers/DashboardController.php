<?php

namespace Modules\Dashboard\Http\Controllers;

use Inertia\Inertia;
use Modules\Page\Models\Page;
use Modules\Visits\Models\Visit;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Dashboard\Actions\GetVisitCountByCountryAction;
use Modules\Dashboard\Actions\GetVisitCountByTimestampAction;
use Modules\Dashboard\Actions\GetVisitsGroupedByDimensionAction;
use Modules\Dashboard\Data\DashboardData;
use Modules\Page\Actions\GetAllPagesWithUnpublishedChangesAction;
use Modules\Page\Enums\PageType;

class DashboardController extends Controller
{

    public function __invoke(Request $request)
    {


        $visits_over_time = app(GetVisitCountByTimestampAction::class)->handle();

        $count_by_country  = app(GetVisitCountByCountryAction::class)->handle();

        // unique views total
        $unique_visitors = Visit::query()->distinct('location_ip')->count('location_ip');
        $total_pageviews = Visit::query()->count();

        // pages with pending changes
        $pagesWithUnpublishedChanges = app(GetAllPagesWithUnpublishedChangesAction::class)->handle(PageType::Page);
        $postsWithUnpublishedChanges = app(GetAllPagesWithUnpublishedChangesAction::class)->handle(PageType::Post);

        return Inertia::render('Dashboard::index', [
            'data' => DashboardData::from([
                'country_data' => $count_by_country,
                'visits_over_time' => $visits_over_time,
                'visits_by_browser' => app(GetVisitsGroupedByDimensionAction::class)->handle('browser'),
                'visits_by_url' => app(GetVisitsGroupedByDimensionAction::class)->handle('url'),
                'stats' => [
                    'total_pageviews' => $total_pageviews,
                    'unique_visitors' => $unique_visitors,
                    'total_pages' => Page::query()->pages()->count(),
                    'total_posts' => Page::query()->posts()->count(),
                    'pending_pages' => count($pagesWithUnpublishedChanges),
                    'pending_posts' => count($postsWithUnpublishedChanges),
                ]
            ])
        ]);
    }
}
