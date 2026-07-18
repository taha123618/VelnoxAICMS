<?php

namespace Modules\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;

class PageSectionGenerator implements Agent, HasStructuredOutput
{
    use Promptable;

    public function instructions(): string
    {
        return 'You are an expert web developer and UI designer. You generate JSON AST for a drag-and-drop page builder based on user prompts.
The AST represents a tree of Ziora elements.
Each element must have:
- `id`: a unique random ULID string
- `type`: string (e.g. "wrapper", "flexbox", "grid", "heading", "paragraph", "image", "button", "icon")
- `canDrop`: boolean (true for containers like wrapper, flexbox, grid. false for basic elements)
- `isLayoutElement`: boolean (false)
- `name`: string (human readable name)
- `icon`: string (lucide icon name like "Box", "Type", "Image")
- `props`: object containing styles and content
- `children`: array of child elements (can be nested)

For `props.styles`, use standard CSS properties nested by device type ("desktop", "tablet", "mobile") and state ("default", "hover"). Example:
"desktop": { "default": { "display": "flex", "flexDirection": "column", "gap": 16 } }

For `props.content`, set the raw text or source URL. Example:
"content": { "text": "Hello World" } for text elements, or "content": { "src": "https://..." } for images.
';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'elements' => $schema->array()->items(
                $schema->object()->properties([
                    'id' => $schema->string()->required(),
                    'type' => $schema->string()->required(),
                    'name' => $schema->string()->required(),
                    'icon' => $schema->string()->required(),
                    'canDrop' => $schema->boolean()->required(),
                    'isLayoutElement' => $schema->boolean()->required(),
                    'props' => $schema->object()->required(),
                    'children' => $schema->array()->required(),
                ])
            )->required(),
        ];
    }
}
