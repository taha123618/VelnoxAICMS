<?php

namespace Modules\Layout\Data;

use Modules\Layout\Models\Layout;
use Modules\Page\Enums\PageType;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript()]
class LayoutData extends Data
{
    public function __construct(
        public int|string $id,
        public string $name,
        public string $totalPages,
        public string $totalPosts,
        public ?string $created,
        public ?string $updated,
        public ?string $statusColor,
        public array|Optional $content,
        public array|Optional $can
    ) {}

    public static function fromModel(Layout $layout): self
    {

        return new self(
            id: $layout->id,
            name: $layout->name,
            totalPages: $layout->pages()->where('type', PageType::Page)->count(),
            totalPosts: $layout->pages()->where('type', PageType::Post)->count(),
            created: $layout->created_at,
            updated: $layout->updated_at,
            statusColor: $layout->getStatus()->getColor(),
            content: $layout->content ?? null,
            can: $layout->authorization,
        );
    }
}
