<?php

namespace Modules\Dashboard\Actions;

use Illuminate\Support\Facades\DB;

class GetVisitCountByCountryAction
{
    public function handle(bool $distinct = false)
    {
        if ($distinct) {
            return DB::table('visits')
                ->select('country_code', DB::raw('COUNT(DISTINCT location_ip) as count'))
                ->groupBy('country_code')
                ->pluck('count', 'country_code')
                ->toArray();
        }

        return DB::table('visits')
            ->select(DB::raw('count(*) as total, country_code'))
            ->groupBy('country_code')
            ->pluck('total', 'country_code')
            ->toArray();
    }
}
