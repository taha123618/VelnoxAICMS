<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Modules\Ai\Jobs\ProcessAiGenerationJob;
use Modules\Auth\Models\User;
use Modules\Marketplace\Agents\MarketplaceGenerator;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('it queues an ai marketplace listing job via API endpoint', function (): void {
    Queue::fake();

    $user = User::factory()->create();

    $response = $this->actingAs($user, 'sanctum')->postJson('/api/marketplace/generate-listing', [
        'prompt' => 'Create a modern dark mode theme for SaaS dashboards',
        'async' => true,
    ]);

    $response->assertStatus(202);
    $response->assertJsonStructure([
        'message',
        'job' => ['id', 'user_id', 'type', 'prompt', 'status', 'progress'],
    ]);

    Queue::assertPushed(ProcessAiGenerationJob::class);
});

test('it generates an ai marketplace listing synchronously when async is false', function (): void {
    MarketplaceGenerator::fake([
        'title' => 'Faked Theme',
        'slug' => 'faked-theme',
        'short_description' => 'A short description',
        'full_description' => 'A full description',
        'category' => 'Theme',
        'features' => [],
        'tags' => [],
    ]);

    $user = User::factory()->create();

    $response = $this->actingAs($user, 'sanctum')->postJson('/api/marketplace/generate-listing', [
        'prompt' => 'Create a modern dark mode theme for SaaS dashboards',
        'async' => false,
    ]);

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'listing' => ['title', 'slug', 'short_description', 'full_description', 'category', 'features', 'tags'],
    ]);
});

test('it rejects empty prompts for marketplace AI generation', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user, 'sanctum')->postJson('/api/marketplace/generate-listing', [
        'prompt' => '',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['prompt']);
});
