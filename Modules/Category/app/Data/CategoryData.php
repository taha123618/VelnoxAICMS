<?php

namespace Modules\Category\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;
use Illuminate\Support\Collection;
use Modules\Category\Models\Category;
use Spatie\LaravelData\Optional;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript()]
class CategoryData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public string $slug,
        public int $totalPosts,
        public string|null $parentId,
        public string|null $parentName,
        public string|null $description,
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
            can: $category->authorization,
            children: Lazy::whenLoaded('children', $category, fn() => CategoryData::collect($category->children)) ?? []
        );
    }
}
