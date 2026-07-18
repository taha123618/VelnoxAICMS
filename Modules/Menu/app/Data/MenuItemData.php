<?php

namespace Modules\Menu\Data;

use Illuminate\Support\Collection;
use Modules\Menu\Enums\MenuItemType;
use Modules\Menu\Models\MenuItem;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript()]
class MenuItemData extends Data
{
    public function __construct(
        public string $id,
        public bool $isRecent,
        public string $menuId,
        public ?string $parentId,
        public MenuItemType $type,
        public string $label,
        public string $path,
        public string $href,
        public string $to,
        public string $target,
        public bool $defaultOpen,
        public Lazy|Collection|null $children,
    ) {}

    public static function fromModel(MenuItem $item): self
    {
        return new self(
            id: $item->id,
            isRecent: false,
            parentId: $item->parent_id,
            menuId: $item->menu_id,
            type: $item->type,
            label: $item->label,
            path: $item->path,
            href: $item->getUrl(),
            to: $item->getUrl(),
            defaultOpen: true,
            target: $item->target,
            children: Lazy::whenLoaded('children', $item, fn () => MenuItemData::collect($item->children()->orderBy('sort_order')->get())) ?? []
        );
    }
}
