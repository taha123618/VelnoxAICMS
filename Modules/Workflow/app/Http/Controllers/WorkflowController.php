<?php

declare(strict_types=1);

namespace Modules\Workflow\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Ai\Models\AiGenerationJob;

class WorkflowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $aiJobs = AiGenerationJob::where('type', 'workflow')
            ->where('status', AiGenerationJob::STATUS_COMPLETED)
            ->latest()
            ->get();

        $workflows = [];
        foreach ($aiJobs as $aiJob) {
            $wf = $aiJob->result['workflow'] ?? $aiJob->result ?? [];
            if (! empty($wf['name'])) {
                $workflows[] = [
                    'id' => $aiJob->id,
                    'name' => $wf['name'],
                    'description' => $wf['description'] ?? 'AI generated workflow',
                    'is_active' => (bool) ($wf['is_active'] ?? true),
                    'trigger' => $wf['trigger'] ?? ['type' => 'custom', 'config' => []],
                    'nodes' => $wf['nodes'] ?? [],
                    'created_at' => $aiJob->created_at?->diffForHumans() ?? 'Recently',
                ];
            }
        }

        return Inertia::render('Workflow::index', [
            'workflows' => $workflows,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Workflow::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'trigger' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'nodes' => ['nullable', 'array'],
        ]);

        $workflowData = [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? 'Custom automation workflow.',
            'trigger' => ['type' => $validated['trigger'] ?? 'custom_event', 'config' => []],
            'nodes' => $validated['nodes'] ?? [],
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ];

        AiGenerationJob::create([
            'user_id' => $request->user()?->id,
            'type' => 'workflow',
            'prompt' => 'Manual workflow creation: '.$validated['name'],
            'status' => AiGenerationJob::STATUS_COMPLETED,
            'progress' => 100,
            'result' => ['workflow' => $workflowData],
        ]);

        return back()->with('success', 'Workflow created successfully.');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return Inertia::render('Workflow::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return Inertia::render('Workflow::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $job = AiGenerationJob::find($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'trigger_type' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'nodes' => ['nullable', 'array'],
        ]);

        if ($job) {
            $existing = $job->result['workflow'] ?? $job->result ?? [];
            $existing['name'] = $validated['name'];
            $existing['description'] = $validated['description'] ?? '';
            $existing['trigger'] = ['type' => $validated['trigger_type'] ?? 'custom_event', 'config' => []];
            $existing['is_active'] = (bool) ($validated['is_active'] ?? false);
            if ($request->has('nodes')) {
                $existing['nodes'] = $validated['nodes'];
            }

            $job->update([
                'result' => ['workflow' => $existing],
            ]);
        }

        return back()->with('success', 'Workflow updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $job = AiGenerationJob::find($id);
        if ($job) {
            $job->delete();
        }

        return back()->with('success', 'Workflow deleted successfully.');
    }
}
