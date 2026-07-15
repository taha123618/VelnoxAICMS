<?php

namespace Modules\Menu\Actions;

use Modules\Menu\Models\Menu;

class GetAllMenusAction
{
    public function handle()
    {
        return Menu::query()->with('items.children')->get();
    }
}
