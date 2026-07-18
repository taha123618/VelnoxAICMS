<?php

namespace Modules\Automation\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Automation\Models\Webhook;
use Inertia\Inertia;

class WebhookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $webhooks = Webhook::latest()->paginate(10);
        return Inertia::render('Automation::index', [
            'webhooks' => $webhooks
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url',
            'events' => 'required|array',
            'secret' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        Webhook::create($validated);

        return redirect()->back()->with('success', 'Webhook created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Webhook $webhook)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url',
            'events' => 'required|array',
            'secret' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $webhook->update($validated);

        return redirect()->back()->with('success', 'Webhook updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Webhook $webhook)
    {
        $webhook->delete();

        return redirect()->back()->with('success', 'Webhook deleted successfully.');
    }
}
