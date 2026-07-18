<?php

namespace Modules\Testimonial\Data;

use App\Enums\Status;
use Modules\Testimonial\Models\Testimonial;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript()]
class TestimonialData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public ?string $avatar,
        public ?string $title,
        public string $comment,
        public Status $status,
        public string $statusColor,
        public string $created_at,
        public bool $isPublished,
        public array|Optional $can
    ) {}

    public static function fromModel(Testimonial $testimonial): self
    {

        return new self(
            id: $testimonial->id,
            name: $testimonial->name,
            avatar: $testimonial->avatar,
            title: $testimonial->title,
            comment: $testimonial->comment,
            status: $testimonial->getStatus(),
            statusColor: $testimonial->getStatus()->getColor(),
            created_at: $testimonial->created_at,
            isPublished: $testimonial->is_published,
            can: $testimonial->authorization
        );
    }
}
