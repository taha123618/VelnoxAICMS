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

it('queues an ai generation job via API endpoint', function () {
    Queue::fake();
    Event::fake();

    $user = User::factory()->create();

    $response = actingAs($user, 'sanctum')->postJson('/api/ai/jobs', [
        'prompt' => 'A modern hero section with headline and CTA button',
        'type' => 'section',
    ]);

    $response->assertStatus(202);
    $response->assertJsonStructure([
        'message',
        'job' => ['id', 'status', 'progress', 'prompt', 'type'],
    ]);

    $jobId = $response->json('job.id');

    $this->assertDatabaseHas('ai_generation_jobs', [
        'id' => $jobId,
        'user_id' => $user->id,
        'status' => 'queued',
        'type' => 'section',
    ]);

    Queue::assertPushed(ProcessAiGenerationJob::class, function ($job) use ($jobId) {
        return $job->jobId === $jobId;
    });
});

it('prevents duplicate active job submissions for the same user and type', function () {
    $user = User::factory()->create();

    AiGenerationJob::create([
        'user_id' => $user->id,
        'type' => 'section',
        'prompt' => 'First active request',
        'status' => 'processing',
        'progress' => 30,
    ]);

    $response = actingAs($user, 'sanctum')->postJson('/api/ai/jobs', [
        'prompt' => 'Second prompt while first is running',
        'type' => 'section',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['prompt']);
});

it('fetches job status via API', function () {
    $user = User::factory()->create();

    $job = AiGenerationJob::create([
        'user_id' => $user->id,
        'type' => 'section',
        'prompt' => 'Test prompt',
        'status' => 'processing',
        'progress' => 50,
    ]);

    $response = actingAs($user, 'sanctum')->getJson("/api/ai/jobs/{$job->id}");

    $response->assertStatus(200);
    $response->assertJson([
        'job' => [
            'id' => $job->id,
            'status' => 'processing',
            'progress' => 50,
        ],
    ]);
});

it('can retry a failed job', function () {
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

    $response = actingAs($user, 'sanctum')->postJson("/api/ai/jobs/{$job->id}/retry");

    $response->assertStatus(202);

    $this->assertDatabaseHas('ai_generation_jobs', [
        'id' => $job->id,
        'status' => 'queued',
        'progress' => 0,
        'error' => null,
    ]);

    Queue::assertPushed(ProcessAiGenerationJob::class);
    Event::assertDispatched(AiJobStatusUpdated::class);
});

it('can cancel an active job', function () {
    Event::fake();

    $user = User::factory()->create();

    $job = AiGenerationJob::create([
        'user_id' => $user->id,
        'type' => 'section',
        'prompt' => 'Long prompt to be cancelled',
        'status' => 'processing',
        'progress' => 30,
    ]);

    $response = actingAs($user, 'sanctum')->postJson("/api/ai/jobs/{$job->id}/cancel");

    $response->assertStatus(200);

    $this->assertDatabaseHas('ai_generation_jobs', [
        'id' => $job->id,
        'status' => 'cancelled',
    ]);

    Event::assertDispatched(AiJobStatusUpdated::class);
});

it('processes the background job and marks it completed', function () {
    Event::fake();

    $user = User::factory()->create();

    $service = new AiGenerationService;
    $job = $service->dispatchJob('Simple heading section', 'section', $user->id);

    // Synchronously execute job
    $processor = new ProcessAiGenerationJob($job->id);
    $processor->handle();

    $job->refresh();

    expect($job->status)->toBe('completed');
    expect($job->progress)->toBe(100);
    expect($job->result)->toBeArray();
    expect($job->result)->toHaveKey('elements');

    Event::assertDispatched(AiJobStatusUpdated::class);
});
