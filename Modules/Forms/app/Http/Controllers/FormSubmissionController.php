<?php

namespace Modules\Forms\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Forms\Models\Form;

class FormSubmissionController extends Controller
{
    /**
     * Store a newly created form submission.
     */
    public function store(Request $request, $slug)
    {
        $form = Form::where('slug', $slug)->firstOrFail();

        if (! $form->is_active) {
            return response()->json(['message' => 'Form is inactive.'], 403);
        }

        // We can add validation based on the $form->schema here later

        $form->submissions()->create([
            'data' => $request->except(['_token']),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => $form->success_message ?? 'Form submitted successfully.',
            ]);
        }

        if ($form->redirect_url) {
            return redirect($form->redirect_url);
        }

        return back()->with('success', $form->success_message ?? 'Form submitted successfully.');
    }
}
