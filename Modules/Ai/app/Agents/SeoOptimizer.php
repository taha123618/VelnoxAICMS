<?php

namespace Modules\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;

class SeoOptimizer implements Agent, HasStructuredOutput
{
    use Promptable;

    public function instructions(): string
    {
        return 'You are an SEO expert. Analyze the provided page content and generate optimized SEO metadata.
Ensure the title is catchy but under 60 characters.
Ensure the description is compelling and under 160 characters.
Provide a list of up to 5 relevant SEO keywords.
Focus on search intent and readability.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'meta_title' => $schema->string()->description('Optimized SEO title, max 60 chars')->required(),
            'meta_description' => $schema->string()->description('Optimized meta description, max 160 chars')->required(),
            'keywords' => $schema->array()->items($schema->string())->description('Up to 5 SEO keywords')->required(),
            'suggestions' => $schema->array()->items($schema->string())->description('Brief suggestions to improve content SEO')->required(),
        ];
    }
}
