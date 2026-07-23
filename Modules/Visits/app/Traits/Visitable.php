<?php

namespace Modules\Visits\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\Visits\Models\Visit;
use Modules\Visits\Visitor;

trait Visitable
{
    public function visitLogs()
    {
        return $this->morphMany(Visit::class, 'visitable');
    }

    protected function scopeWithTotalVisitCount(Builder $builder): void
    {
        $builder->withCount('visits as visit_count_total');
    }

    public function createVisitLog(?Model $model)
    {
        return resolve(Visitor::class)->setVisitor($model)->visit($this);
    }
}
