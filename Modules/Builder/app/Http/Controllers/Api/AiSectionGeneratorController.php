<?php

namespace Modules\Builder\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Builder\Agents\PageSectionGenerator;
use Illuminate\Http\JsonResponse;

class AiSectionGeneratorController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'prompt' => ['required', 'string', 'max:2000'],
        ]);

        try {
            $agent = new PageSectionGenerator();
            
            // Pass the user's prompt to the agent
            $response = $agent->prompt($request->input('prompt'));
            
            // The structured output ensures we get an array of 'elements'
            return response()->json([
                'elements' => $response['elements'] ?? []
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
