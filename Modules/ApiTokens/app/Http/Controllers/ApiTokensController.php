<?php

namespace Modules\ApiTokens\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ApiTokensController extends Controller
{
    /**
     * Display the API tokens dashboard.
     */
    public function index(Request $request)
    {
        return Inertia::render('ApiTokens::index', [
            'tokens' => $request->user()->tokens,
        ]);
    }

    /**
     * Store a new API token.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $token = $request->user()->createToken($request->name);

        return back()->with('flash', [
            'token' => $token->plainTextToken,
            'message' => 'Token created successfully. Please copy it now as it will not be shown again.',
        ]);
    }

    /**
     * Revoke a specific API token.
     */
    public function destroy(Request $request, $id)
    {
        $request->user()->tokens()->where('id', $id)->delete();

        return back()->with('success', 'Token revoked successfully.');
    }
}
