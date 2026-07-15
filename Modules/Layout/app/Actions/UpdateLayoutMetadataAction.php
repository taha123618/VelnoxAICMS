<?php

namespace Modules\Layout\Actions;

use Modules\Layout\Models\Layout;
use Modules\Layout\Http\Requests\UpdateLayoutMetadataRequest;

class UpdateLayoutMetadataAction
{
    public function handle(UpdateLayoutMetadataRequest $request, Layout $layout)
    {
        return tap($layout)->update([
            'name' => $request->name
        ]);
    }
}
