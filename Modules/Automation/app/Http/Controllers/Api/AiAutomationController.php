<?php

declare(strict_types=1);

namespace Modules\Automation\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Ai\Services\AiGenerationService;
use Modules\Automation\Agents\AutomationRuleGenerator;
use Modules\Automation\Models\Webhook;
use PromptPHP\Intercept\Exceptions\InterceptException;
use PromptPHP\Intercept\InjectionGuard\Exceptions\PromptInjectionGuardException;

class AiAutomationController extends Controller
{
    public function __construct(protected AiGenerationService $aiService) {}

    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'prompt' => ['required', 'string', 'max:2000'],
            'async' => ['nullable', 'boolean'],
        ]);

        // Support async queued mode by default unless explicitly disabled with async=false
        if ($request->boolean('async', false)) {
            $job = $this->aiService->dispatchJob(
                prompt: $request->input('prompt'),
                type: 'automation',
                userId: $request->user()?->id
            );

            return response()->json([
                'message' => 'AI automation rule generation queued successfully.',
                'job' => $job,
            ], 202);
        }

        try {
            $automationRuleGenerator = new AutomationRuleGenerator;
            $response = $automationRuleGenerator->prompt($request->input('prompt'));

            if (! empty($response['rule_name']) || ! empty($response['event_trigger'])) {
                Webhook::create([
                    'name' => $response['rule_name'] ?? 'AI Automation Listener',
                    'url' => 'https://api.myapp.com/webhooks/'.($response['event_trigger'] ?? 'automation'),
                    'events' => [$response['event_trigger'] ?? 'entry.created'],
                    'secret' => 'whsec_ai_'.Str::random(10),
                    'is_active' => true,
                ]);
            }

            return response()->json([
                'automation' => $response,
            ]);
        } catch (PromptInjectionGuardException|InterceptException) {
            return response()->json([
                'message' => 'Your request could not be processed because it appears to contain unsafe prompt instructions.',
            ], 422);
        }
    }
}
