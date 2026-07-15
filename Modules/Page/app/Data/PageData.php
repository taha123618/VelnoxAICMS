<?php

namespace Modules\Page\Data;

use App\Enums\Status;
use Spatie\LaravelData\Data;
use Modules\Page\Models\Page;
use Spatie\LaravelData\Optional;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript()]
class PageData extends Data
{
    public function __construct(
        public string $id,
        public string|Optional $type,
        public string $layoutId,
        public string $layoutName,
        public string $slug,
        public string $title,
        public Status $status,
        public string $statusColor,
        public string $url,
        public string|null $description,
        public array $content,
        public bool $isFrontpage,
        public bool $isPublished,
        public array|Optional $keywords,
        public array|Optional $can,
        public string $created_at,
        public string $updated_at,
        public bool $isDifferentFromPublishedVersion,
    ) {}

    public static function fromModel(Page $page): self
    {
        return new self(
            id: $page->id,
            type: 'page',
            layoutId: $page->layout_id,
            layoutName: $page->layout->name,
            slug: $page->slug,
            title: $page->title,
            status: $page->getStatus(),
            statusColor: $page->getStatus()->getColor(),
            url: $page->getUrl(false),
            description: $page->description,
            content: $page->content,
            isFrontpage: $page->is_frontpage,
            isPublished: $page->is_published,
            keywords: $page->data['keywords'] ?? [],
            created_at: $page->created_at,
            updated_at: $page->updated_at,
            isDifferentFromPublishedVersion: $page->isDifferentFromPublishedVersion(),
            can: $page->page_authorization,
        );
    }
}
