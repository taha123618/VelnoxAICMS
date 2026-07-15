<?php

namespace Modules\Contacts\Actions;

use Modules\Contacts\Models\Contact;

class DeleteContactAction
{
    public function handle(Contact $contact)
    {
        $contact->delete();
    }
}
