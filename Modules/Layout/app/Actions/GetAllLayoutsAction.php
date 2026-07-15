<?php

namespace Modules\Layout\Actions;

use Modules\Layout\Models\Layout;

class GetAllLayoutsAction
{
    public function handle()
    {
        return Layout::orderBy('name')->get();
    }
}
