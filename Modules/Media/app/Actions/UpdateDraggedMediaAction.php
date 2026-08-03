<?php

namespace Modules\Media\Actions;

use Illuminate\Http\Request;
use Modules\Media\Models\Folder;
use Modules\Media\Models\Media;

class UpdateDraggedMediaAction
{
    public function handle(Request $request): void
    {

        $destinationId = $request->destinationId;

        if ($request->type == 'folder') {
            $folder = Folder::find($request->sourceId);

            $folder?->update([
                'parent_id' => $destinationId,
            ]);
        } else {
            $media = Media::find($request->sourceId);

            $media?->update([
                'model_id' => $destinationId,
            ]);
        }
    }
}
