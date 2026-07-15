<?php

namespace Modules\Contacts\Actions;

use Modules\Contacts\Http\Requests\CreateContactRequest;
use Modules\Contacts\Models\Contact;

class CreateContactAction
{
    public function handle(CreateContactRequest $request)
    {
        return Contact::create([
            'name' => $request->name,
            'subject' => $request->subject,
            'email' => $request->email,
            'body' => $request->body,
            'subscribe_to_mail' => $request->subscribe_to_mail,
        ]);
    }
}
