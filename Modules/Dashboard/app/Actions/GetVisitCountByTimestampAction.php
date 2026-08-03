<?php

declare(strict_types=1);

namespace Modules\Dashboard\Actions;

use Illuminate\Support\Facades\DB;

class GetVisitCountByTimestampAction
{
    public function handle()
    {

        return DB::table('visits')
            ->selectRaw(
                'created_at as grouped_time, 
                    COUNT(*) as total_views
                '
            )
            ->groupBy('grouped_time')
            ->orderBy('grouped_time')
            ->get()
            ->map(fn ($row): array => [
                'x' => strtotime((string) $row->grouped_time) * 1000,
                'y' => $row->total_views,
            ]);
    }
}
