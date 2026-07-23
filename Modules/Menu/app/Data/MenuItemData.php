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
        public int|string $id,
        public bool $isRecent,
        public string $menuId,
        public int|string|null $parentId,
        public MenuItemType $type,
        public string $label,
        public string $path,
        public string $href,
        public string $to,
        public string $target,
        public bool $defaultOpen,
        public Lazy|Collection|null $children,
    ) {}

    public static function fromModel(MenuItem $menuItem): self
    {
        return new self(
            id: $menuItem->id,
            isRecent: false,
            menuId: $menuItem->menu_id,
            parentId: $menuItem->parent_id,
            type: $menuItem->type,
            label: $menuItem->label,
            path: $menuItem->path,
            href: $menuItem->getUrl(),
            to: $menuItem->getUrl(),
            target: $menuItem->target,
            defaultOpen: true,
            children: Lazy::whenLoaded('children', $menuItem, fn (): array|\Illuminate\Contracts\Pagination\CursorPaginator|\Illuminate\Contracts\Pagination\Paginator|\Illuminate\Pagination\AbstractCursorPaginator|\Illuminate\Pagination\AbstractPaginator|\Illuminate\Support\Enumerable|\Spatie\LaravelData\CursorPaginatedDataCollection|\Spatie\LaravelData\DataCollection|\Spatie\LaravelData\PaginatedDataCollection => MenuItemData::collect($menuItem->children()->orderBy('sort_order')->get())) ?? []
        );
    }
}
