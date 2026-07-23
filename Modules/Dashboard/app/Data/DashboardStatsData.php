<?php

declare(strict_types=1);

namespace Modules\Dashboard\Data;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript()]
class DashboardStatsData extends Data
{
    public function __construct(
        public ?int $total_pageviews,
        public ?int $unique_visitors,
        public ?int $pending_pages,
        public ?int $pending_posts,
        public ?int $total_pages,
        public ?int $total_posts,
    ) {}
}
