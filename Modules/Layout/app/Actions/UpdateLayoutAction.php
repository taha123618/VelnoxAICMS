<?php

namespace Modules\Layout\Actions;

use Modules\Layout\Http\Requests\UpdateLayoutRequest;
use Modules\Layout\Models\Layout;

class UpdateLayoutAction
{
    public function handle(UpdateLayoutRequest $request, Layout $layout)
    {

        return tap($layout)->update([
            'content' => $request->content,
        ]);

    }
}
