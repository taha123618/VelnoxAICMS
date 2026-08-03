<?php

namespace Modules\Menu\Actions;

use Modules\Menu\Http\Requests\UpdateMenuRequest;
use Modules\Menu\Models\Menu;
use Modules\Menu\Models\MenuItem;

class UpdateMenuAction
{
    public function handle(UpdateMenuRequest $updateMenuRequest, Menu $menu): void
    {
        $menu->update([
            'name' => $updateMenuRequest->name,
        ]);

        if (empty($updateMenuRequest->items)) {
            $menu->items()->delete();

            return;
        }

        if (is_array($updateMenuRequest->deleted) && count($updateMenuRequest->deleted)) {
            MenuItem::whereIn('id', $updateMenuRequest->deleted)->delete();
        }

        $idMap = [];

        foreach ($updateMenuRequest->items as $index => $item) {
            $this->processMenuItem($item, null, $idMap, $index);
        }
    }

    private function processMenuItem(array $item, ?string $parentId, array &$idMap, int $sortOrder): void
    {
        // If parentId is in the map, use the actual DB ID
        if ($parentId && isset($idMap[$parentId])) {
            $parentId = $idMap[$parentId];
        }

        if ($item['isRecent']) {
            $menuItem = MenuItem::create([
                'parent_id' => $parentId,
                'menu_id' => $item['menuId'],
                'type' => $item['type'],
                'path' => $item['path'],
                'label' => $item['label'],
                'target' => $item['target'],
                'sort_order' => $sortOrder,
            ]);

            // Store mapping of temp ID to real DB ID
            $idMap[$item['id']] = $menuItem->id;
        } else {
            $menuItem = MenuItem::updateOrCreate(
                ['id' => $item['id']],
                [
                    'parent_id' => $parentId,
                    'path' => $item['path'],
                    'label' => $item['label'],
                    'target' => $item['target'],
                    'sort_order' => $sortOrder,
                ]
            );
        }

        // Recursively process children
        $children = $item['children'] ?? [];
        if (is_array($children) && count($children)) {
            foreach ($item['children'] as $childIndex => $child) {
                $this->processMenuItem($child, $item['id'], $idMap, $childIndex);
            }
        }
    }
}
