<?php

namespace Modules\Dashboard\Actions;

use Illuminate\Support\Facades\DB;

class GetVisitCountByTimestampAction
{
    public function handle()
    {

        return DB::table('visits')
            ->selectRaw(
                "UNIX_TIMESTAMP(created_at) as grouped_time, 
                    COUNT(*) as total_views
                "
            )
            ->groupBy('grouped_time')
            ->orderBy('grouped_time')
            ->get()
            ->map(function ($row) {
                return [
                    'x' => strtotime($row->grouped_time) * 1000,
                    'y' => $row->total_views,
                ];
            });
    }
}
