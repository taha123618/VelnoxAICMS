<?php

namespace Modules\Layout\Actions;

use Modules\Layout\Http\Requests\UpdateLayoutMetadataRequest;
use Modules\Layout\Models\Layout;

class UpdateLayoutMetadataAction
{
    public function handle(UpdateLayoutMetadataRequest $request, Layout $layout)
    {
        return tap($layout)->update([
            'name' => $request->name,
        ]);
    }
}
