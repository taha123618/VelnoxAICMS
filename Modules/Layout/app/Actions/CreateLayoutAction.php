<?php

namespace Modules\Layout\Actions;

use Modules\Layout\Http\Requests\CreateLayoutRequest;
use Modules\Layout\Models\Layout;

class CreateLayoutAction
{
    public function handle(CreateLayoutRequest $createLayoutRequest)
    {

        return Layout::create([
            'name' => $createLayoutRequest->name,
            'content' => $createLayoutRequest->content,
        ]);
    }
}
