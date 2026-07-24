<?php

declare(strict_types=1);

namespace Modules\Menu\Actions;

use Modules\Menu\Models\Menu;

class DeleteMenuAction
{
    public function handle(Menu $menu): void
    {
        $menu->delete();
    }
}
