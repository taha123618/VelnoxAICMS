<?php

declare(strict_types=1);

namespace Modules\Media\Actions;

use Modules\Media\Models\Folder;

class DeleteFolderAction
{
    public function handle(Folder $folder): void
    {

        $folder->delete();
    }
}
