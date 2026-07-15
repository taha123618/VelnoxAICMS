<?php

namespace Modules\Layout\Actions;

use Modules\Layout\Models\Layout;

class GetLayoutDropdownOptionsAction
{
    public function handle()
    {

        return Layout::query()->get()->select('id', 'name');
    }
}
