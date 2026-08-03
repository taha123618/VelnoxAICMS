<?php

declare(strict_types=1);

namespace Modules\Automation\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use PromptPHP\Intercept\InjectionGuard\PromptInjectionGuard;
use PromptPHP\Intercept\PIIRedactor\PIIRedactor;

class AutomationRuleGenerator implements Agent, HasStructuredOutput
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
        return 'You are an Automation & Lifecycle Marketing Strategist for VelnoxAICMS.
Generate automation rules including trigger event, delay timing, action type, and email template content if applicable.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'rule_name' => $schema->string()->description('Automation rule title')->required(),
            'event_trigger' => $schema->string()->description('Triggering event, e.g. user_registered, form_submitted')->required(),
            'delay_minutes' => $schema->number()->description('Delay in minutes before executing action')->required(),
            'action_type' => $schema->string()->description('Action type: send_email, update_user_role, webhook')->required(),
            'email_subject' => $schema->string()->description('Subject line if action is email')->required(),
            'email_body' => $schema->string()->description('Body content of email template')->required(),
        ];
    }
}
