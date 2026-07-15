<?php

namespace Modules\Layout\Actions;

use Modules\Layout\Models\Layout;
use Modules\Layout\Http\Requests\CreateLayoutRequest;

class CreateLayoutAction
{
    public function handle(CreateLayoutRequest $request)
    {

        return Layout::create([
            'name' => $request->name,
            'content' => $request->content
        ]);
    }
}
