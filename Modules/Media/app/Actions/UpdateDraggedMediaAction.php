<?php

namespace Modules\Media\Actions;

use Illuminate\Http\Request;
use Modules\Media\Models\Media;
use Modules\Media\Models\Folder;

class UpdateDraggedMediaAction
{
    public function handle(Request $request)
    {

        $destinationId = $request->destinationId;

        if ($request->type == 'folder') {
            $folder = Folder::find($request->sourceId);

            $folder?->update([
                'parent_id' => $destinationId
            ]);
        } else {
            $media = Media::find($request->sourceId);

            $media?->update([
                'model_id' => $destinationId
            ]);
        }
    }
}
