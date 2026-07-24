<?php

namespace Modules\Contacts\Actions;

use Modules\Contacts\Http\Requests\CreateContactRequest;
use Modules\Contacts\Models\Contact;

class CreateContactAction
{
    public function handle(CreateContactRequest $createContactRequest)
    {
        return Contact::create([
            'name' => $createContactRequest->name,
            'subject' => $createContactRequest->subject,
            'email' => $createContactRequest->email,
            'body' => $createContactRequest->body,
            'subscribe_to_mail' => $createContactRequest->subscribe_to_mail,
        ]);
    }
}
