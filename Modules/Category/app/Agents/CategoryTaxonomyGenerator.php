<?php

declare(strict_types=1);

namespace Modules\Category\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use PromptPHP\Intercept\InjectionGuard\PromptInjectionGuard;
use PromptPHP\Intercept\PIIRedactor\PIIRedactor;

class CategoryTaxonomyGenerator implements Agent, HasStructuredOutput
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
        return 'You are a Taxonomy & Information Architecture Specialist.
Generate a structured category tree including main category details and optional subcategories based on the topic prompt.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'name' => $schema->string()->description('Primary Category Name')->required(),
            'slug' => $schema->string()->description('Category URL slug')->required(),
            'description' => $schema->string()->description('Category description for SEO')->required(),
            'subcategories' => $schema->array(
                $schema->object([
                    'name' => $schema->string()->required(),
                    'slug' => $schema->string()->required(),
                    'description' => $schema->string()->required(),
                ])
            )->description('List of child subcategories')->required(),
        ];
    }
}
