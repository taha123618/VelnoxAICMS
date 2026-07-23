<?php

namespace Modules\Category\Data;

use Illuminate\Support\Collection;
use Modules\Category\Models\Category;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Optional;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript()]
class CategoryData extends Data
{
    public function __construct(
        public int|string $id,
        public string $name,
        public string $slug,
        public int $totalPosts,
        public int|string|null $parentId,
        public ?string $parentName,
        public ?string $description,
        public Lazy|Collection|null $children,
        public Optional|array $can,

    ) {}

    public static function fromModel(Category $category): self
    {
        return new self(
            id: $category->id,
            name: $category->name,
            slug: $category->slug,
            totalPosts: count($category->posts),
            parentId: $category->parent?->id,
            parentName: $category->parent?->name,
            description: $category->description,
            children: Lazy::whenLoaded('children', $category, fn (): array|\Illuminate\Contracts\Pagination\CursorPaginator|\Illuminate\Contracts\Pagination\Paginator|\Illuminate\Pagination\AbstractCursorPaginator|\Illuminate\Pagination\AbstractPaginator|\Illuminate\Support\Enumerable|\Spatie\LaravelData\CursorPaginatedDataCollection|\Spatie\LaravelData\DataCollection|\Spatie\LaravelData\PaginatedDataCollection => CategoryData::collect($category->children)) ?? [],
            can: $category->authorization
        );
    }
}
