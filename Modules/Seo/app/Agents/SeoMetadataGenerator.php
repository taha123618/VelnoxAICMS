<?php

declare(strict_types=1);

namespace Modules\Seo\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use PromptPHP\Intercept\InjectionGuard\PromptInjectionGuard;
use PromptPHP\Intercept\PIIRedactor\PIIRedactor;

class SeoMetadataGenerator implements Agent, HasStructuredOutput
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
        return 'You are a Senior Technical SEO Strategist for VelnoxAICMS.
Generate optimized meta title, meta description, OpenGraph tags, target keywords, and JSON-LD schema markup for search engine indexing.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'meta_title' => $schema->string()->description('Meta title under 60 chars')->required(),
            'meta_description' => $schema->string()->description('Meta description under 160 chars')->required(),
            'og_title' => $schema->string()->description('OpenGraph title for social shares')->required(),
            'og_description' => $schema->string()->description('OpenGraph description for social shares')->required(),
            'keywords' => $schema->array()->items($schema->string())->description('List of target keywords')->required(),
            'json_ld_type' => $schema->string()->description('Schema.org type, e.g. Article, WebPage, Product, Organization')->required(),
            'suggestions' => $schema->array()->items($schema->string())->description('SEO improvement recommendations')->required(),
        ];
    }
}
