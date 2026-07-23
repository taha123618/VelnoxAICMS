<?php

namespace Modules\AuditLog\Http\Controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Spatie\Activitylog\Models\Activity;

class AuditLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lengthAwarePaginator = Activity::with('causer')
            ->latest()
            ->paginate(50)
            ->through(fn ($activity): array => [
                'id' => $activity->id,
                'description' => $activity->description,
                'subject_type' => class_basename($activity->subject_type),
                'subject_id' => $activity->subject_id,
                'causer_name' => $activity->causer ? $activity->causer->name : 'System',
                'properties' => $activity->properties,
                'created_at' => $activity->created_at->toIso8601String(),
            ]);

        return Inertia::render('AuditLog::index', [
            'logs' => $lengthAwarePaginator,
        ]);
    }
}
