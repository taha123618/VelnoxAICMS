<?php

namespace Modules\Visits\Models;

use App\Models\BaseModel;
use Illuminate\Support\Arr;

// use Modules\Visits\Database\Factories\VisitFactory;

class Visit extends BaseModel
{

    protected $casts = [
        'data' => 'json',
        'request'   => 'array',
        'languages' => 'array',
        'headers'   => 'array',
    ];
    public function visitable()
    {
        return $this->morphTo('visitable');
    }

    public function visitor()
    {
        return $this->morphTo('visitor');
    }

    public function getData(string $key)
    {
        if (!$this->data || !is_array($this->data)) {
            return null;
        }
        return Arr::get($this->data, $key);
    }
}
