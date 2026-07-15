<?php

namespace Modules\Page\Data;

use App\Enums\Status;
use Modules\Page\Enums\PageType;
use Spatie\LaravelData\Data;
use Modules\Page\Models\Page;
use Spatie\LaravelData\Optional;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript()]
class PostData extends Data
{
    public function __construct(
        public string $id,
        public string|Optional $type,
        public string $layoutId,
        public string $layoutName,
        public string $categoryName,
        public string $categoryId,
        public string $slug,
        public string $title,
        public Status $status,
        public string $statusColor,
        public string $url,
        public bool $isPublished,
        public string|null $description,
        public string|null $excerpt,
        public string|null $featuredImage,
        public array $content,
        public array|Optional $keywords,
        public string $created_at,
        public bool $isDifferentFromPublishedVersion,
        public array|Optional $can
    ) {}

    public static function fromModel(Page $model): self
    {
        return new self(
            id: $model->id,
            type: PageType::Post->value,
            categoryId: $model->category->id,
            categoryName: $model->category->name,
            layoutName: $model->layout->name,
            layoutId: $model->layout_id,
            slug: $model->slug,
            title: $model->title,
            status: $model->getStatus(),
            statusColor: $model->getStatus()->getColor(),
            url: $model->getUrl(false),
            description: $model->description,
            excerpt: $model->excerpt,
            featuredImage: $model->featured_image ?? '/images/no-image.png',
            content: $model->content,
            isPublished: $model->is_published,
            keywords: $model->data['keywords'] ?? [],
            created_at: $model->created_at,
            isDifferentFromPublishedVersion: $model->isDifferentFromPublishedVersion(),
            can: $model->post_authorization,
        );
    }
}
