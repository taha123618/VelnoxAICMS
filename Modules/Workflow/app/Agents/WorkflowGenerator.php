<?php

declare(strict_types=1);

namespace Modules\Workflow\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use PromptPHP\Intercept\InjectionGuard\PromptInjectionGuard;
use PromptPHP\Intercept\PIIRedactor\PIIRedactor;

class WorkflowGenerator implements Agent, HasStructuredOutput
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
        return 'You are an Automation & Workflow Architect for ZioraCMS.
Your task is to generate a visual workflow tree based on the user\'s prompt.
The workflow consists of a trigger node, condition steps, and action execution steps.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'name' => $schema->string()->description('Workflow name')->required(),
            'description' => $schema->string()->description('Workflow purpose summary')->required(),
            'trigger' => $schema->object([
                'type' => $schema->string()->description('Event trigger type, e.g. user_registered, form_submitted, order_placed')->required(),
                'config' => $schema->object([])->description('Trigger parameters')->required(),
            ])->required(),
            'nodes' => $schema->array(
                $schema->object([
                    'id' => $schema->string()->description('Node unique ID')->required(),
                    'type' => $schema->string()->description('Node type: condition, action, delay')->required(),
                    'label' => $schema->string()->description('Display label')->required(),
                    'config' => $schema->object([])->description('Step configuration data')->required(),
                ])
            )->description('Workflow execution steps')->required(),
        ];
    }
}
