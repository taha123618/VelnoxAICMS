<?php

namespace Modules\Media\Actions;

use Modules\Media\Models\Folder;

class DeleteFolderAction
{
    public function handle(Folder $folder)
    {

        $folder->delete();
    }
}
