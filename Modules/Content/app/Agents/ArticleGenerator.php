<?php

declare(strict_types=1);

namespace Modules\Content\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use PromptPHP\Intercept\InjectionGuard\PromptInjectionGuard;
use PromptPHP\Intercept\PIIRedactor\PIIRedactor;

class ArticleGenerator implements Agent, HasStructuredOutput
{
    use Promptable;

    public function middleware(): array
    {
        return [
            new PromptInjectionGuard(action: 'block'),
            new PIIRedactor(action: 'redact', blockEntities: ['credit_card', 'api_key', 'bearer_token']),
        ];
    }

    public function instructions(): string
    {
        return 'You are an Expert Content Editor and Copywriter for VelnoxAICMS.
Generate a high-quality blog article or content post complete with headline, excerpt, full markdown content body, reading time estimate, and tags.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'title' => $schema->string()->description('Article title')->required(),
            'slug' => $schema->string()->description('SEO friendly URL slug')->required(),
            'excerpt' => $schema->string()->description('Brief summary under 160 characters')->required(),
            'content' => $schema->string()->description('Full markdown content body')->required(),
            'estimated_reading_minutes' => $schema->number()->description('Estimated reading time in minutes')->required(),
            'tags' => $schema->array()->items($schema->string())->description('Topic tags')->required(),
        ];
    }
}
