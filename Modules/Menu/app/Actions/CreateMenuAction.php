<?php

namespace Modules\Menu\Actions;

use Illuminate\Http\Request;
use Modules\Menu\Models\Menu;

class CreateMenuAction
{
    public function handle(Request $request)
    {
        return Menu::create([
            'name' => $request->name,
        ]);
    }
}
