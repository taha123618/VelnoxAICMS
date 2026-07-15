<?php

namespace Modules\Contacts\Data;

use Modules\Contacts\Models\Contact;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript()]
class ContactData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public string $email,
        public string $subject,
        public string $body,
        public bool $isSubscribed,
        public string $created_at,
        public string $updated_at
    ) {}

    public static function fromModel(Contact $model): self
    {
        return new self(
            id: $model->id,
            name: $model->name,
            email: $model->email,
            subject: $model->subject,
            body: $model->body,
            isSubscribed: $model->subscribe_to_mail,
            created_at: $model->created_at,
            updated_at: $model->updated_at,
        );
    }
}
