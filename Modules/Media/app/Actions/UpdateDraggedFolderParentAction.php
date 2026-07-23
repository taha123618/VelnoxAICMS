<?php

declare(strict_types=1);

namespace Modules\Media\Actions;

use Illuminate\Http\Request;
use Modules\Media\Models\Folder;

class UpdateDraggedFolderParentAction
{
    public function handle(Request $request, Folder $folder): void
    {

        $folder->update([
            'parent_id' => $request->parentId,
        ]);
    }
}
