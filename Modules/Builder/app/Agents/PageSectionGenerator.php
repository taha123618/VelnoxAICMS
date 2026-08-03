<?php

namespace Modules\Builder\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasMiddleware;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use PromptPHP\Intercept\InjectionGuard\PromptInjectionGuard;
use PromptPHP\Intercept\PIIRedactor\PIIRedactor;

class PageSectionGenerator implements Agent, HasMiddleware, HasStructuredOutput
{
    use Promptable;

    public function middleware(): array
    {
        return [
            // production
            new PromptInjectionGuard(
                action: 'block',
            ),

            new PIIRedactor(
                action: 'redact',
                blockEntities: [
                    'credit_card',
                    'api_key',
                    'bearer_token',
                ],
            ),
        ];
    }

    public function instructions(): string
    {
        return 'You are an expert web UI builder AI. Your task is to generate an array of VelnoxAICMS page builder elements based on the user\'s prompt. 
Each element must follow the TElement schema closely. 
Available types: "wrapper" (a container, can have children), "grid" (a CSS grid, can have children), "flexbox", "paragraph" (a rich text element), "heading" (a headline element), "link" (use this for both text links AND buttons), "image", "video".
The root element you generate should usually be a "wrapper" that acts as a section, containing other elements.
For grids, the children are placed into the grid cells.
Provide a "props" object for styling and content.
CRITICAL RULES FOR PROPS:
1. For text content (heading, paragraph, link), you MUST use "content": { "innerText": "Your text here" }. Do NOT use "text".
2. For styling, put CSS properties (camelCase) directly in the "props" object (e.g. "color", "textAlign", "padding", "backgroundColor"). Do NOT create a nested "style" or "styles" object.
3. For layout, include structural props directly in "props" if needed (e.g., "tag": "h1").
Example Element:
{
    "type": "wrapper",
    "name": "Hero Section",
    "isLayoutElement": false,
    "canDrop": true,
    "children": [
        {
            "type": "heading",
            "name": "Hero Title",
            "isLayoutElement": false,
            "canDrop": false,
            "children": [],
            "props": { 
                "tag": "h1",
                "content": { "innerText": "Welcome to our site" },
                "color": "#333333",
                "textAlign": "center",
                "paddingTop": "20px"
            }
        }
    ],
    "props": {
        "backgroundColor": "#f8f9fa",
        "padding": "40px"
    }
}';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'elements' => $schema->array(
                $schema->object([
                    'type' => $schema->string()->description('The element type, e.g. wrapper, grid, flexbox, paragraph, heading, link, image, video')->required(),
                    'name' => $schema->string()->description('A descriptive name for the element')->required(),
                    'isLayoutElement' => $schema->boolean()->description('Always false for generated content blocks')->required(),
                    'canDrop' => $schema->boolean()->description('True if it can contain children (like wrapper, grid, flexbox), false otherwise')->required(),
                    'children' => $schema->array(
                        $schema->object([
                            'type' => $schema->string()->required(),
                            'name' => $schema->string()->required(),
                            'isLayoutElement' => $schema->boolean()->required(),
                            'canDrop' => $schema->boolean()->required(),
                            'children' => $schema->array($schema->object([]))->description('Nested children')->required(),
                            'props' => $schema->object([])->description('Element properties, including content.innerText and flat camelCase CSS styles directly on this object')->required(),
                        ])
                    )->description('Child elements')->required(),
                    'props' => $schema->object([])->description('Element properties, including flat camelCase CSS styles directly on this object')->required(),
                ])
            )->description('The generated root-level elements (usually just one wrapper section)')->required(),
        ];
    }
}
