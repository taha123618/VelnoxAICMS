<?php

namespace Modules\Layout\Actions;

use Modules\Layout\Http\Requests\CreateLayoutRequest;
use Modules\Layout\Models\Layout;

class CreateLayoutAction
{
    public function handle(CreateLayoutRequest $request)
    {

        return Layout::create([
            'name' => $request->name,
            'content' => $request->content,
        ]);
    }
}
