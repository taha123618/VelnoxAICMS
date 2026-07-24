<?php

namespace Modules\Page\Data;

use App\Enums\Status;
use Modules\Page\Enums\PageType;
use Modules\Page\Models\Page;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript()]
class PostData extends Data
{
    public function __construct(
        public int|string $id,
        public string|Optional $type,
        public int|string $layoutId,
        public string $layoutName,
        public string $categoryName,
        public string $categoryId,
        public string $slug,
        public string $title,
        public Status $status,
        public string $statusColor,
        public string $url,
        public bool $isPublished,
        public ?string $description,
        public ?string $excerpt,
        public ?string $featuredImage,
        public array $content,
        public array|Optional $keywords,
        public string $created_at,
        public bool $isDifferentFromPublishedVersion,
        public array|Optional $can
    ) {}

    public static function fromModel(Page $page): self
    {
        return new self(
            id: $page->id,
            type: PageType::Post->value,
            layoutId: $page->layout_id,
            layoutName: $page->layout->name,
            categoryName: $page->category->name,
            categoryId: $page->category->id,
            slug: $page->slug,
            title: $page->title,
            status: $page->getStatus(),
            statusColor: $page->getStatus()->getColor(),
            url: $page->getUrl(false),
            isPublished: $page->is_published,
            description: $page->description,
            excerpt: $page->excerpt,
            featuredImage: $page->featured_image ?? '/images/no-image.png',
            content: $page->content,
            keywords: $page->data['keywords'] ?? [],
            created_at: $page->created_at,
            isDifferentFromPublishedVersion: $page->isDifferentFromPublishedVersion(),
            can: $page->post_authorization,
        );
    }
}
