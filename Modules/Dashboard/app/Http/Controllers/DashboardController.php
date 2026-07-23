<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Dashboard\Actions\GetVisitCountByCountryAction;
use Modules\Dashboard\Actions\GetVisitCountByTimestampAction;
use Modules\Dashboard\Actions\GetVisitsGroupedByDimensionAction;
use Modules\Dashboard\Data\DashboardData;
use Modules\Page\Actions\GetAllPagesWithUnpublishedChangesAction;
use Modules\Page\Enums\PageType;
use Modules\Page\Models\Page;
use Modules\Visits\Models\Visit;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {

        $visits_over_time = resolve(GetVisitCountByTimestampAction::class)->handle();

        $count_by_country = resolve(GetVisitCountByCountryAction::class)->handle();

        // unique views total
        $unique_visitors = Visit::query()->distinct('location_ip')->count('location_ip');
        $total_pageviews = Visit::query()->count();

        // pages with pending changes
        $pagesWithUnpublishedChanges = resolve(GetAllPagesWithUnpublishedChangesAction::class)->handle(PageType::Page);
        $postsWithUnpublishedChanges = resolve(GetAllPagesWithUnpublishedChangesAction::class)->handle(PageType::Post);

        return Inertia::render('Dashboard::index', [
            'data' => DashboardData::from([
                'country_data' => $count_by_country,
                'visits_over_time' => $visits_over_time,
                'visits_by_browser' => resolve(GetVisitsGroupedByDimensionAction::class)->handle('browser'),
                'visits_by_url' => resolve(GetVisitsGroupedByDimensionAction::class)->handle('url'),
                'stats' => [
                    'total_pageviews' => $total_pageviews,
                    'unique_visitors' => $unique_visitors,
                    'total_pages' => Page::query()->pages()->count(),
                    'total_posts' => Page::query()->posts()->count(),
                    'pending_pages' => count($pagesWithUnpublishedChanges),
                    'pending_posts' => count($postsWithUnpublishedChanges),
                ],
            ]),
        ]);
    }
}
