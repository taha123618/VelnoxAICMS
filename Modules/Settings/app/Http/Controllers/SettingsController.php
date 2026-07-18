<?php

namespace Modules\Settings\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Settings\Models\Setting;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $settings = Setting::all()->groupBy('group');

        return Inertia::render('Settings::index', [
            'settings' => $settings
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*.key' => 'required|string',
            'settings.*.value' => 'nullable',
            'settings.*.type' => 'required|string',
            'settings.*.group' => 'required|string',
        ]);

        foreach ($validated['settings'] as $settingData) {
            $setting = Setting::updateOrCreate(
                ['key' => $settingData['key']],
                [
                    'group' => $settingData['group'],
                    'value' => is_array($settingData['value']) ? json_encode($settingData['value']) : $settingData['value'],
                    'type' => $settingData['type']
                ]
            );

            // Handle file uploads for image settings
            if ($settingData['type'] === 'image' && $request->hasFile("files.{$settingData['key']}")) {
                $setting->clearMediaCollection('default');
                $setting->addMedia($request->file("files.{$settingData['key']}"))->toMediaCollection('default');
            }
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
