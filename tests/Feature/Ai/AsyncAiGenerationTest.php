<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Modules\Ai\Events\AiJobStatusUpdated;
use Modules\Ai\Jobs\ProcessAiGenerationJob;
use Modules\Ai\Models\AiGenerationJob;
use Modules\Ai\Services\AiGenerationService;
use Modules\Auth\Models\User;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

it('queues an ai generation job via API endpoint', function (): void {
    Queue::fake();
    Event::fake();

    $user = User::factory()->create();

    $testResponse = actingAs($user, 'sanctum')->postJson('/api/ai/jobs', [
        'prompt' => 'A modern hero section with headline and CTA button',
        'type' => 'section',
    ]);

    $testResponse->assertStatus(202);
    $testResponse->assertJsonStructure([
        'message',
        'job' => ['id', 'status', 'progress', 'prompt', 'type'],
    ]);

    $jobId = $testResponse->json('job.id');

    $this->assertDatabaseHas('ai_generation_jobs', [
        'id' => $jobId,
        'user_id' => $user->id,
        'status' => 'queued',
        'type' => 'section',
    ]);

    Queue::assertPushed(ProcessAiGenerationJob::class, fn ($job): bool => $job->jobId === $jobId);
});

it('prevents duplicate active job submissions for the same user and type', function (): void {
    $user = User::factory()->create();

    AiGenerationJob::create([
        'user_id' => $user->id,
        'type' => 'section',
        'prompt' => 'First active request',
        'status' => 'processing',
        'progress' => 30,
    ]);

    $testResponse = actingAs($user, 'sanctum')->postJson('/api/ai/jobs', [
        'prompt' => 'Second prompt while first is running',
        'type' => 'section',
    ]);

    $testResponse->assertStatus(422);
    $testResponse->assertJsonValidationErrors(['prompt']);
});

it('fetches job status via API', function (): void {
    $user = User::factory()->create();

    $job = AiGenerationJob::create([
        'user_id' => $user->id,
        'type' => 'section',
        'prompt' => 'Test prompt',
        'status' => 'processing',
        'progress' => 50,
    ]);

    $testResponse = actingAs($user, 'sanctum')->getJson("/api/ai/jobs/{$job->id}");

    $testResponse->assertStatus(200);
    $testResponse->assertJson([
        'job' => [
            'id' => $job->id,
            'status' => 'processing',
            'progress' => 50,
        ],
    ]);
});

it('can retry a failed job', function (): void {
    Queue::fake();
    Event::fake();

    $user = User::factory()->create();

    $job = AiGenerationJob::create([
        'user_id' => $user->id,
        'type' => 'section',
        'prompt' => 'Failed request prompt',
        'status' => 'failed',
        'error' => 'Connection timed out',
        'progress' => 20,
    ]);

    $testResponse = actingAs($user, 'sanctum')->postJson("/api/ai/jobs/{$job->id}/retry");

    $testResponse->assertStatus(202);

    $this->assertDatabaseHas('ai_generation_jobs', [
        'id' => $job->id,
        'status' => 'queued',
        'progress' => 0,
        'error' => null,
    ]);

    Queue::assertPushed(ProcessAiGenerationJob::class);
    Event::assertDispatched(AiJobStatusUpdated::class);
});

it('can cancel an active job', function (): void {
    Event::fake();

    $user = User::factory()->create();

    $job = AiGenerationJob::create([
        'user_id' => $user->id,
        'type' => 'section',
        'prompt' => 'Long prompt to be cancelled',
        'status' => 'processing',
        'progress' => 30,
    ]);

    $testResponse = actingAs($user, 'sanctum')->postJson("/api/ai/jobs/{$job->id}/cancel");

    $testResponse->assertStatus(200);

    $this->assertDatabaseHas('ai_generation_jobs', [
        'id' => $job->id,
        'status' => 'cancelled',
    ]);

    Event::assertDispatched(AiJobStatusUpdated::class);
});

it('processes the background job and marks it completed', function (): void {
    Event::fake();

    $user = User::factory()->create();

    $service = new AiGenerationService;
    $aiGenerationJob = $service->dispatchJob('Simple heading section', 'section', $user->id);

    // Synchronously execute job
    $processor = new ProcessAiGenerationJob($aiGenerationJob->id);
    $processor->handle();

    $aiGenerationJob->refresh();

    expect($aiGenerationJob->status)->toBe('completed');
    expect($aiGenerationJob->progress)->toBe(100);
    expect($aiGenerationJob->result)->toBeArray();
    expect($aiGenerationJob->result)->toHaveKey('elements');

    Event::assertDispatched(AiJobStatusUpdated::class);
});
