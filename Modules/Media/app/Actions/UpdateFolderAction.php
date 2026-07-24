<?php

namespace Modules\Media\Actions;

use Modules\Media\Http\Requests\UpdateFolderRequest;
use Modules\Media\Models\Folder;

class UpdateFolderAction
{
    public function handle(UpdateFolderRequest $updateFolderRequest, Folder $folder)
    {

        return tap($folder)->update([
            'name' => $updateFolderRequest->name,
        ]);
    }
}
