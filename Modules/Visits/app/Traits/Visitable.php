<?php

namespace Modules\Visits\Traits;

use Modules\Visits\Visitor;
use Modules\Visits\Models\Visit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

trait Visitable
{

    public function visitLogs()
    {
        return $this->morphMany(Visit::class, 'visitable');
    }


    public function scopeWithTotalVisitCount(Builder $query)
    {
        $query->withCount('visits as visit_count_total');
    }


    public function createVisitLog(?Model $visitor)
    {
        return app(Visitor::class)->setVisitor($visitor)->visit($this);
    }



}
