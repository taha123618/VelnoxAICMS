<?php

namespace Modules\Marketplace\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;
use Modules\Ai\Models\AiGenerationJob;
use Nwidart\Modules\Facades\Module;

class MarketplaceController extends Controller
{
    public function index()
    {
        $modules = Module::all();
        $formattedPlugins = [];
        $formattedThemes = [];

        foreach ($modules as $module) {
            $isTheme = str_contains(strtolower((string) $module->getName()), 'theme') || str_contains(strtolower((string) $module->getDescription()), 'theme');

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

        // Include AI-generated listings from completed jobs so they persist on hard refresh
        $aiJobs = AiGenerationJob::where('type', 'marketplace')
            ->where('status', AiGenerationJob::STATUS_COMPLETED)
            ->latest()
            ->get();

        foreach ($aiJobs as $aiJob) {
            $listing = $aiJob->result['listing'] ?? $aiJob->result ?? [];
            $name = $listing['title'] ?? $listing['name'] ?? $listing['plugin_name'] ?? $listing['theme_name'] ?? null;
            if (empty($name)) {
                continue;
            }

            $isTheme = str_contains(strtolower((string) ($listing['category'] ?? '')), 'theme')
                    || str_contains(strtolower((string) $name), 'theme');

            $formattedData = [
                'name' => $name,
                'alias' => $listing['slug'] ?? ('ai-item-'.$aiJob->id),
                'description' => $listing['short_description'] ?? $listing['description'] ?? $listing['full_description'] ?? 'AI Generated listing',
                'path' => '',
                'is_enabled' => false,
                'version' => '1.0.0 (AI)',
                'job_id' => $aiJob->id,
                // Full spec for client-side JSON download
                'ai_spec' => [
                    'title' => $name,
                    'slug' => $listing['slug'] ?? null,
                    'category' => $listing['category'] ?? null,
                    'short_description' => $listing['short_description'] ?? null,
                    'full_description' => $listing['full_description'] ?? null,
                    'features' => $listing['features'] ?? [],
                    'tags' => $listing['tags'] ?? [],
                    'suggested_price' => $listing['suggested_price'] ?? null,
                    'generated_at' => $aiJob->updated_at?->toIso8601String(),
                ],
            ];

            if ($isTheme) {
                array_unshift($formattedThemes, $formattedData);
            } else {
                array_unshift($formattedPlugins, $formattedData);
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
            'plugin' => ['required', 'file', 'mimes:zip', 'max:50000'],
        ]);

        $zipPath = $request->file('plugin')->getRealPath();
        $zipArchive = new \ZipArchive;

        if ($zipArchive->open($zipPath) === true) {
            // Find the module name from the root folder inside the zip
            // In a real scenario, we should extract to a temp dir, read module.json, and rename if necessary.
            // For now, we will just extract it to the Modules directory.
            $destination = base_path('Modules');
            $zipArchive->extractTo($destination);
            $zipArchive->close();

            // Reload modules
            \Artisan::call('module:optimize');

            return back()->with('success', 'Plugin installed successfully!');
        }

        return back()->with('error', 'Failed to open the zip file.');
    }

    /**
     * Delete an extension or AI listing.
     */
    public function destroy($name)
    {
        // First check if it is an AI listing job ID
        $aiJob = AiGenerationJob::where('id', $name)
            ->orWhere(function ($query) use ($name): void {
                $query->where('type', 'marketplace')
                    ->where('result->listing->slug', $name);
            })
            ->first();

        if ($aiJob) {
            $aiJob->delete();

            return back()->with('success', 'AI listing concept deleted.');
        }

        $module = Module::find($name);
        if (! $module) {
            return back()->with('error', 'Plugin not found.');
        }

        // Prevent deleting core modules
        $coreModules = [
            'Acl', 'Ai', 'ApiTokens', 'AuditLog', 'Auth', 'Automation',
            'Builder', 'Category', 'Contacts', 'Content', 'Dashboard',
            'Forms', 'Layout', 'Localization', 'Marketplace', 'Media',
            'Menu', 'Page', 'Seo', 'Settings', 'Testimonial', 'Visits',
            'Workflow',
        ];

        if (in_array(strtolower($module->getName()), array_map(strtolower(...), $coreModules))) {
            return back()->with('error', 'Cannot delete core system modules.');
        }

        $path = $module->getPath();
        if (File::exists($path)) {
            File::deleteDirectory($path);
        }

        // Optimize modules map
        \Artisan::call('module:optimize');

        return back()->with('success', 'Plugin deleted successfully from disk.');
    }
}
