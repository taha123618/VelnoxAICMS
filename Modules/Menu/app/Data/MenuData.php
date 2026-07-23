<?php

namespace Modules\Menu\Data;

use Illuminate\Support\Collection;
use Modules\Menu\Models\Menu;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Optional;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript()]
class MenuData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public Lazy|Collection $items,
        public int $totalItems,
        public string $created_at,
        public array|Optional $can
    ) {
        //
    }

    public static function fromModel(Menu $menu): self
    {
        return new self(
            id: $menu->id,
            name: $menu->name,
            items: Lazy::whenLoaded('items', $menu, fn () => MenuItemData::collect($menu->items()->isRoot()->with('children')->orderBy('sort_order')->get())),
            totalItems: count($menu->items),
            created_at: $menu->created_at,
            can: $menu->authorization
        );
    }
}
