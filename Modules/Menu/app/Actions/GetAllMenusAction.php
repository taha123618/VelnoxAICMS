<?php

declare(strict_types=1);

namespace Modules\Menu\Actions;

use Modules\Menu\Models\Menu;

class GetAllMenusAction
{
    public function handle()
    {
        return Menu::query()->with('items.children')->get();
    }
}
