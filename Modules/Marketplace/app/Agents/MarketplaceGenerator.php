<?php

declare(strict_types=1);

namespace Modules\Marketplace\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use PromptPHP\Intercept\InjectionGuard\PromptInjectionGuard;
use PromptPHP\Intercept\PIIRedactor\PIIRedactor;

class MarketplaceGenerator implements Agent, HasStructuredOutput
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
        return 'You are an expert Marketplace Product & Listing Strategist for ZioraCMS.
Analyze the user prompt to generate a complete, professional marketplace plugin, theme, or extension listing.
Generate a compelling title, short excerpt, full feature list (array of strings), search tags, and recommended pricing tiers.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'title' => $schema->string()->description('Marketplace item title')->required(),
            'slug' => $schema->string()->description('URL friendly slug')->required(),
            'short_description' => $schema->string()->description('Catchy summary excerpt under 150 chars')->required(),
            'full_description' => $schema->string()->description('Detailed overview of features and capabilities')->required(),
            'category' => $schema->string()->description('Primary category, e.g. Theme, Plugin, Integration, Widget')->required(),
            'features' => $schema->array()->items($schema->string())->description('List of key features')->required(),
            'tags' => $schema->array()->items($schema->string())->description('Up to 5 relevant search tags')->required(),
            'suggested_price' => $schema->number()->description('Suggested listing price in USD')->required(),
        ];
    }
}
