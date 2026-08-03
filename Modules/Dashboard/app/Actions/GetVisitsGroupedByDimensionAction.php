<?php

declare(strict_types=1);

namespace Modules\Dashboard\Actions;

use Illuminate\Support\Facades\DB;

class GetVisitsGroupedByDimensionAction
{
    public function handle(string $dimension)
    {

        return DB::table('visits')
            ->select(DB::raw("count(*) as total, {$dimension}"))
            ->groupBy("{$dimension}")
            ->orderBy('total', 'desc')
            ->get();
    }
}
