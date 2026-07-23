<?php

declare(strict_types=1);

namespace Modules\Contacts\Actions;

use Modules\Contacts\Models\Contact;

class DeleteContactAction
{
    public function handle(Contact $contact): void
    {
        $contact->delete();
    }
}
