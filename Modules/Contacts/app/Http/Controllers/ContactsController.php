<?php

namespace Modules\Contacts\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
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

        $data = app(SearchContactsAction::class)->handle($request);

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

    public function store(CreateContactRequest $request)
    {

        app(CreateContactAction::class)->handle($request);

        return Redirect::back();
    }

    public function destroy(Contact $contact)
    {

        app(DeleteContactAction::class)->handle($contact);

        return Redirect::back();
    }
}
