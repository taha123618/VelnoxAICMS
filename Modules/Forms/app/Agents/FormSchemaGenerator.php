<?php

declare(strict_types=1);

namespace Modules\Forms\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use PromptPHP\Intercept\InjectionGuard\PromptInjectionGuard;
use PromptPHP\Intercept\PIIRedactor\PIIRedactor;

class FormSchemaGenerator implements Agent, HasStructuredOutput
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
        return 'You are a Form Design and UX Expert for VelnoxAICMS.
Generate a form configuration schema including form title, description, submit button text, and field inputs array.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'form_title' => $schema->string()->description('Form title')->required(),
            'description' => $schema->string()->description('Form summary or subtext')->required(),
            'submit_button_text' => $schema->string()->description('Submit button text')->required(),
            'fields' => $schema->array(
                $schema->object([
                    'name' => $schema->string()->description('Field key name')->required(),
                    'label' => $schema->string()->description('Field label')->required(),
                    'type' => $schema->string()->description('Field type: text, email, textarea, select, checkbox, radio')->required(),
                    'placeholder' => $schema->string()->description('Input placeholder text')->required(),
                    'required' => $schema->boolean()->description('Whether field is required')->required(),
                    'options' => $schema->array()->items($schema->string())->description('Options for select/radio/checkbox if applicable')->required(),
                ])
            )->description('Form field definitions')->required(),
        ];
    }
}
