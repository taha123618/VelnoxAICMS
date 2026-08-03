<?php

namespace Modules\Layout\Actions;

use Modules\Layout\Http\Requests\UpdateLayoutMetadataRequest;
use Modules\Layout\Models\Layout;

class UpdateLayoutMetadataAction
{
    public function handle(UpdateLayoutMetadataRequest $updateLayoutMetadataRequest, Layout $layout)
    {
        return tap($layout)->update([
            'name' => $updateLayoutMetadataRequest->name,
        ]);
    }
}
