<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Modules\Ai\Jobs\ProcessAiGenerationJob;
use Modules\Auth\Models\User;
use Modules\Seo\Agents\SeoMetadataGenerator;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('it queues an ai seo metadata generation job via API endpoint', function (): void {
    Queue::fake();

    $user = User::factory()->create();

    $response = $this->actingAs($user, 'sanctum')->postJson('/api/seo/generate-metadata', [
        'prompt' => 'Generate SEO metadata for a SaaS AI builder product landing page',
        'async' => true,
    ]);

    $response->assertStatus(202);
    $response->assertJsonStructure([
        'message',
        'job' => ['id', 'user_id', 'type', 'prompt', 'status'],
    ]);

    Queue::assertPushed(ProcessAiGenerationJob::class);
});

test('it generates an ai seo metadata synchronously when async is false', function (): void {
    SeoMetadataGenerator::fake([
        'meta_title' => 'Faked Title',
        'meta_description' => 'Faked Description',
        'og_title' => 'Faked OG Title',
        'og_description' => 'Faked OG Description',
        'keywords' => [],
        'json_ld_type' => 'Product',
        'suggestions' => [],
    ]);

    $user = User::factory()->create();

    $response = $this->actingAs($user, 'sanctum')->postJson('/api/seo/generate-metadata', [
        'prompt' => 'Generate SEO metadata for a SaaS AI builder product landing page',
        'async' => false,
    ]);

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'meta' => ['meta_title', 'meta_description', 'og_title', 'og_description', 'keywords', 'json_ld_type', 'suggestions'],
    ]);
});
