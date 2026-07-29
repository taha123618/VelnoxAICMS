<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Modules\Ai\Jobs\ProcessAiGenerationJob;
use Modules\Auth\Models\User;
use Modules\Testimonial\Agents\TestimonialGenerator;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('it queues an ai testimonial generation job via API endpoint', function (): void {
    Queue::fake();

    $user = User::factory()->create();

    $response = $this->actingAs($user, 'sanctum')->postJson('/api/testimonial/generate-testimonial', [
        'prompt' => 'Generate a testimonial for a SaaS platform',
        'async' => true,
    ]);

    $response->assertStatus(202);
    $response->assertJsonStructure([
        'message',
        'job_id',
        'status',
    ]);

    Queue::assertPushed(ProcessAiGenerationJob::class);
});

test('it generates an ai testimonial synchronously when async is false', function (): void {
    TestimonialGenerator::fake([
        'name' => 'Faked Customer',
        'title' => 'CEO',
        'avatar' => 'https://example.com/avatar.jpg',
        'rating' => 5,
        'comment' => 'Amazing CMS!',
        'company' => 'Faked Corp',
        'status' => 'published',
    ]);

    $user = User::factory()->create();

    $response = $this->actingAs($user, 'sanctum')->postJson('/api/testimonial/generate-testimonial', [
        'prompt' => 'Generate a testimonial for a SaaS platform',
        'async' => false,
    ]);

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'result' => ['testimonial' => ['name', 'title', 'avatar', 'rating', 'comment', 'company', 'status']],
    ]);
});

test('it rejects empty prompts for testimonial AI generation', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user, 'sanctum')->postJson('/api/testimonial/generate-testimonial', [
        'prompt' => '',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['prompt']);
});
