<?php

namespace Modules\Menu\Actions;

use Modules\Menu\Models\Menu;

class DeleteMenuAction
{
    public function handle(Menu $menu)
    {
        $menu->delete();
    }
}
