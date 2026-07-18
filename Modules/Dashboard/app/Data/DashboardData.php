<?php

namespace Modules\Dashboard\Data;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript()]
class DashboardData extends Data
{
    public function __construct(
        public array $country_data,
        public array|Collection $visits_over_time,
        public array|Collection $visits_by_browser,
        public array|Collection $visits_by_url,
        public DashboardStatsData $stats
    ) {}
}
