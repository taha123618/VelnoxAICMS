<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Modules\Ai\Jobs\ProcessAiGenerationJob;
use Modules\Auth\Models\User;
use Modules\Content\Agents\ArticleGenerator;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('it queues an ai content article generation job via API endpoint', function (): void {
    Queue::fake();

    $user = User::factory()->create();

    $response = $this->actingAs($user, 'sanctum')->postJson('/api/content/generate-article', [
        'prompt' => 'Write a technical guide about modern clean CSS styling principles in 2026',
        'async' => true,
    ]);

    $response->assertStatus(202);
    $response->assertJsonStructure([
        'message',
        'job' => ['id', 'user_id', 'type', 'prompt', 'status'],
    ]);

    Queue::assertPushed(ProcessAiGenerationJob::class);
});

test('it generates an ai article synchronously when async is false', function (): void {
    ArticleGenerator::fake([
        'title' => 'Faked Article',
        'slug' => 'faked-article',
        'excerpt' => 'A short summary',
        'content' => '# Markdown Body',
        'estimated_reading_minutes' => 3,
        'tags' => [],
    ]);

    $user = User::factory()->create();

    $response = $this->actingAs($user, 'sanctum')->postJson('/api/content/generate-article', [
        'prompt' => 'Write a technical guide about modern clean CSS styling principles in 2026',
        'async' => false,
    ]);

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'article' => ['title', 'slug', 'excerpt', 'content', 'estimated_reading_minutes', 'tags'],
    ]);
});
