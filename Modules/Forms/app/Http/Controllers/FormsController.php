<?php

namespace Modules\Forms\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Forms\Models\Form;

class FormsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $forms = Form::withCount('submissions')->latest()->get();

        return Inertia::render('Forms::index', [
            'forms' => $forms,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'unique:forms,slug', 'max:255'],
            'description' => ['nullable', 'string'],
            'schema' => ['nullable', 'array'],
            'is_active' => ['boolean'],
            'success_message' => ['nullable', 'string', 'max:255'],
            'redirect_url' => ['nullable', 'url'],
        ]);

        Form::create($validated);

        return back()->with('success', 'Form created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $form = Form::with(['submissions' => function ($q): void {
            $q->latest();
        }])->findOrFail($id);

        return Inertia::render('Forms::show', [
            'form' => $form,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $form = Form::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => 'required|string|max:255|unique:forms,slug,'.$form->id,
            'description' => ['nullable', 'string'],
            'schema' => ['nullable', 'array'],
            'is_active' => ['boolean'],
            'success_message' => ['nullable', 'string', 'max:255'],
            'redirect_url' => ['nullable', 'url'],
        ]);

        $form->update($validated);

        return back()->with('success', 'Form updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Form::findOrFail($id)->delete();

        return to_route('admin.forms.index')->with('success', 'Form deleted successfully.');
    }
}
