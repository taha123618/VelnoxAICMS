<?php

declare(strict_types=1);

namespace Modules\Media\Actions;

use Modules\Media\Models\Folder;

class GetFolderBreadcrumbsAction
{
    public function handle(Folder $folder)
    {
        return $folder->getBreadcrumbs();
    }
}
