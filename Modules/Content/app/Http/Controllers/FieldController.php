<?php

namespace Modules\Content\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Content\Models\Collection;
use Modules\Content\Models\Field;

class FieldController extends Controller
{
    public function index(Collection $collection)
    {
        $fields = $collection->fields()->get()->map(function ($field): array {
            $rulesArray = $field->validation_rules ?? [];

            return [
                'id' => $field->id,
                'name' => $field->name,
                'handle' => $field->handle,
                'type' => $field->type,
                'is_required' => in_array('required', $rulesArray),
                'rules' => collect($rulesArray)->reject(fn ($r): bool => $r === 'required')->implode('|'),
                'settings' => $field->ui_config ?? [],
            ];
        });

        return Inertia::render('Content::fields/index', [
            'collection' => $collection,
            'fields' => $fields,
        ]);
    }

    public function store(Request $request, Collection $collection)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'handle' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string'],
            'is_required' => ['boolean'],
            'rules' => ['nullable', 'string'],
            'settings' => ['nullable', 'array'],
        ]);

        $rules = [];
        if (! empty($validated['is_required'])) {
            $rules[] = 'required';
        }
        if (! empty($validated['rules'])) {
            $parsedRules = explode('|', $validated['rules']);
            $rules = array_merge($rules, $parsedRules);
        }

        $collection->fields()->create([
            'name' => $validated['name'],
            'handle' => $validated['handle'],
            'type' => $validated['type'],
            'validation_rules' => array_unique(array_filter($rules)),
            'ui_config' => $validated['settings'] ?? [],
        ]);

        return back()->with('success', 'Field added successfully.');
    }

    public function update(Request $request, Collection $collection, Field $field)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'handle' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string'],
            'is_required' => ['boolean'],
            'rules' => ['nullable', 'string'],
            'settings' => ['nullable', 'array'],
        ]);

        $rules = [];
        if (! empty($validated['is_required'])) {
            $rules[] = 'required';
        }
        if (! empty($validated['rules'])) {
            $parsedRules = explode('|', $validated['rules']);
            $rules = array_merge($rules, $parsedRules);
        }

        $field->update([
            'name' => $validated['name'],
            'handle' => $validated['handle'],
            'type' => $validated['type'],
            'validation_rules' => array_unique(array_filter($rules)),
            'ui_config' => $validated['settings'] ?? [],
        ]);

        return back()->with('success', 'Field updated successfully.');
    }

    public function destroy(Collection $collection, Field $field)
    {
        $field->delete();

        return back()->with('success', 'Field deleted successfully.');
    }
}
