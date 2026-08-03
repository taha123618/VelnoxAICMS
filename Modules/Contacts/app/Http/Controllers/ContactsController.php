<?php

namespace Modules\Contacts\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Contacts\Actions\CreateContactAction;
use Modules\Contacts\Actions\DeleteContactAction;
use Modules\Contacts\Actions\SearchContactsAction;
use Modules\Contacts\Data\ContactData;
use Modules\Contacts\Http\Requests\CreateContactRequest;
use Modules\Contacts\Models\Contact;

class ContactsController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'sort']);

        $data = resolve(SearchContactsAction::class)->handle($request);

        return Inertia::render('Contacts::index', [
            'data' => ContactData::collect($data),
            'filters' => $filters,
        ]);
    }

    public function show(Contact $contact)
    {
        return Inertia::render('Contacts::show', [
            'contact' => ContactData::fromModel($contact),
        ]);
    }

    public function store(CreateContactRequest $createContactRequest): RedirectResponse
    {

        resolve(CreateContactAction::class)->handle($createContactRequest);

        return back();
    }

    public function destroy(Contact $contact): RedirectResponse
    {

        resolve(DeleteContactAction::class)->handle($contact);

        return back();
    }
}
