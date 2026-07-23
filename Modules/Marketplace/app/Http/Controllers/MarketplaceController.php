<?php

namespace Modules\Marketplace\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Nwidart\Modules\Facades\Module;

class MarketplaceController extends Controller
{
    public function index()
    {
        $modules = Module::all();
        $formattedPlugins = [];
        $formattedThemes = [];

        foreach ($modules as $module) {
            $isTheme = str_contains(strtolower($module->getName()), 'theme') || str_contains(strtolower($module->getDescription()), 'theme');

            $formattedData = [
                'name' => $module->getName(),
                'alias' => $module->getLowerName(),
                'description' => $module->getDescription(),
                'path' => $module->getPath(),
                'is_enabled' => $module->isEnabled(),
                'version' => $module->get('version', '1.0.0'),
            ];

            if ($isTheme) {
                $formattedThemes[] = $formattedData;
            } else {
                $formattedPlugins[] = $formattedData;
            }
        }

        return Inertia::render('Marketplace::index', [
            'plugins' => $formattedPlugins,
            'themes' => $formattedThemes,
            'activeTheme' => config('ziora.active_theme', 'Default Theme'),
        ]);
    }

    /**
     * Toggle the enabled/disabled state of a module.
     */
    public function toggle(Request $request, $moduleName)
    {
        $module = Module::find($moduleName);

        if (! $module) {
            return back()->with('error', 'Module not found.');
        }

        // Prevent disabling core modules if needed
        if (in_array($moduleName, ['Ai', 'Builder', 'Marketplace', 'Page', 'Layout'])) {
            return back()->with('error', 'Cannot disable core system modules.');
        }

        if ($module->isEnabled()) {
            $module->disable();
        } else {
            $module->enable();
        }

        return back()->with('success', 'Module status updated.');
    }

    /**
     * Upload and install a new module via ZIP.
     */
    public function install(Request $request)
    {
        $request->validate([
            'plugin' => 'required|file|mimes:zip|max:50000',
        ]);

        $zipPath = $request->file('plugin')->getRealPath();
        $zip = new \ZipArchive;

        if ($zip->open($zipPath) === true) {
            // Find the module name from the root folder inside the zip
            // In a real scenario, we should extract to a temp dir, read module.json, and rename if necessary.
            // For now, we will just extract it to the Modules directory.
            $destination = base_path('Modules');
            $zip->extractTo($destination);
            $zip->close();

            // Reload modules
            \Artisan::call('module:optimize');

            return back()->with('success', 'Plugin installed successfully!');
        } else {
            return back()->with('error', 'Failed to open the zip file.');
        }
    }
}
