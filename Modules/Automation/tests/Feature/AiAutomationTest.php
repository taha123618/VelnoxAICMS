<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Modules\Ai\Jobs\ProcessAiGenerationJob;
use Modules\Auth\Models\User;
use Modules\Automation\Agents\AutomationRuleGenerator;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('it queues an ai automation rule generation job via API endpoint', function (): void {
    Queue::fake();

    $user = User::factory()->create();

    $response = $this->actingAs($user, 'sanctum')->postJson('/api/automation/generate-rule', [
        'prompt' => 'Create an abandoned cart re-engagement email sequence rule',
        'async' => true,
    ]);

    $response->assertStatus(202);
    $response->assertJsonStructure([
        'message',
        'job' => ['id', 'user_id', 'type', 'prompt', 'status'],
    ]);

    Queue::assertPushed(ProcessAiGenerationJob::class);
});

test('it generates an ai automation rule synchronously when async is false', function (): void {
    AutomationRuleGenerator::fake([
        'rule_name' => 'Faked Rule',
        'event_trigger' => 'user_registered',
        'delay_minutes' => 5,
        'action_type' => 'send_email',
        'email_subject' => 'Welcome!',
        'email_body' => 'Welcome message',
    ]);

    $user = User::factory()->create();

    $response = $this->actingAs($user, 'sanctum')->postJson('/api/automation/generate-rule', [
        'prompt' => 'Create an abandoned cart re-engagement email sequence rule',
        'async' => false,
    ]);

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'automation' => ['rule_name', 'event_trigger', 'delay_minutes', 'action_type', 'email_subject', 'email_body'],
    ]);
});
