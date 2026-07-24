<?php

namespace Modules\Contacts\Data;

use Modules\Contacts\Models\Contact;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript()]
class ContactData extends Data
{
    public function __construct(
        public int|string $id,
        public string $name,
        public string $email,
        public string $subject,
        public string $body,
        public bool $isSubscribed,
        public string $created_at,
        public string $updated_at
    ) {}

    public static function fromModel(Contact $contact): self
    {
        return new self(
            id: $contact->id,
            name: $contact->name,
            email: $contact->email,
            subject: $contact->subject,
            body: $contact->body,
            isSubscribed: $contact->subscribe_to_mail,
            created_at: $contact->created_at,
            updated_at: $contact->updated_at,
        );
    }
}
