<?php

declare(strict_types=1);

namespace Modules\Testimonial\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use PromptPHP\Intercept\InjectionGuard\PromptInjectionGuard;
use PromptPHP\Intercept\PIIRedactor\PIIRedactor;

class TestimonialGenerator implements Agent, HasStructuredOutput
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
        return 'You are an expert testimonial generator for a modern CMS platform.
Given a user prompt describing a product, service, customer experience, or case study, generate realistic and convincing customer testimonial content.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'name' => $schema->string()->description('Customer full name')->required(),
            'title' => $schema->string()->description('Customer role or company')->required(),
            'avatar' => $schema->string()->description('Avatar URL')->required(),
            'rating' => $schema->integer()->description('Rating score out of 5')->required(),
            'comment' => $schema->string()->description('Detailed customer review text')->required(),
            'company' => $schema->string()->description('Customer company name')->required(),
            'status' => $schema->string()->description('Publication status e.g. published')->required(),
        ];
    }

    public function generate(string $prompt): array
    {
        $agentResponse = $this->prompt($prompt);

        return [
            'testimonial' => $agentResponse ?? [],
        ];
    }
}
