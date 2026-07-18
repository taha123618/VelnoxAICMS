<?php

namespace Modules\Media\Actions;

use Modules\Media\Models\Folder;

class GetFolderTreeAction
{
    public function handle()
    {
        $tree = Folder::query()
            ->tree()
            ->get()
            ->toTree();

        return $this->cleanFolderTree($tree);
    }

    private function cleanFolderTree($nodes)
    {
        return $nodes->map(function ($node) {
            $data = $node->only(['id', 'name', 'parent_id']);
            $data['defaultExpanded'] = true;
            $data['contentCount'] = $node->getTotalChildren();
            $data['type'] = 'FolderTree';
            if ($node->relationLoaded('children')) {
                $data['children'] = $this->cleanFolderTree($node->children);
            }

            return $data;
        });
    }
}
