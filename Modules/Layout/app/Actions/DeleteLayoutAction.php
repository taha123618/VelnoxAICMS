<?php

declare(strict_types=1);

namespace Modules\Layout\Actions;

use Modules\Layout\Models\Layout;

class DeleteLayoutAction
{
    public function handle(Layout $layout): void
    {

        $layout->delete();
    }
}
