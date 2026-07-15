<?php

namespace Modules\Layout\Actions;

use Modules\Layout\Models\Layout;

class DeleteLayoutAction
{
    public function handle(Layout $layout)
    {

        $layout->delete();
    }
}
