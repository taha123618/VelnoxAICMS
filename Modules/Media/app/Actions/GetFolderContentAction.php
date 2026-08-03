<?php

namespace Modules\Media\Actions;

use Modules\Media\Models\Folder;

class GetFolderContentAction
{
    public function handle(Folder $folder)
    {

        $folderContent = $folder->children()->with('user')->get();

        $fileContent = $folder->media;

        $fileContent = $fileContent->map(fn ($content): array => [
            'id' => $content->id,
            'name' => $content->file_name,
            'type' => $content->extension,
            'isDirectory' => false,
            'size' => $content->humanReadableSize,
            'url' => $content->getUrl(),
            'path' => $content->getUrl(),
            'createdAt' => $content->created_at->format('M d, Y H:i'),
            'modifiedAt' => $content->updated_at->format('M d, Y H:i'),
            'thumbnail' => $content->getAvailableUrl(['thumbnail']),
            'can' => [
                'delete' => true,
                'update' => true,
            ],
        ]);

        $folderContent = $folderContent->map(fn ($content): array => [
            'id' => $content->id,
            'name' => $content->name,
            'owner' => $content->user?->name,
            'createdAt' => $content->created_at->format('M d, Y H:i'),
            'modifiedAt' => $content->updated_at->format('M d, Y H:i'),
            'type' => 'Folder',
            'isDirectory' => true,
            'can' => $content->authorization,
            'contentCount' => $content->getTotalChildren(),
            'size' => '-',
        ]);

        return $folderContent->concat($fileContent);
    }
}
