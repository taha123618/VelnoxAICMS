<?php

namespace Modules\Media\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Media\Actions\DeleteMediaAction;
use Modules\Media\Data\MediaData;
use Modules\Media\Models\Folder;
use Modules\Media\Models\Media;

class MediaController extends Controller
{
    public function store(Request $request, Folder $folder)
    {

        $fileName = $request->input('file_name');
        $chunkIndex = $request->input('chunk_index');
        $totalChunks = $request->input('total_chunks');

        $tempFilePath = storage_path("app/public/tmp/{$fileName}");

        $chunk = $request->file('file');

        file_put_contents($tempFilePath, file_get_contents($chunk), FILE_APPEND);

        if ($chunkIndex + 1 == $totalChunks) {
            $media = $folder->addMedia($tempFilePath)->toMediaCollection();

            return MediaData::fromModel($media);
        }

        return response()->noContent();
    }

    public function destroy(Request $request, Media $media)
    {
        resolve(DeleteMediaAction::class)->handle($media);

        return back()->with('success', 'File deleted!');
    }
}
