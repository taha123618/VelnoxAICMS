<?php

namespace Modules\Media\Actions;

use Modules\Media\Models\Folder;
use Modules\Media\Http\Requests\UpdateFolderRequest;

class UpdateFolderAction
{
    public function handle(UpdateFolderRequest $request, Folder $folder)
    {

        return tap($folder)->update([
            'name' => $request->name
        ]);
    }
}
