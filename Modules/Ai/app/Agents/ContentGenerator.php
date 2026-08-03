<?php

declare(strict_types=1);

namespace Modules\Ai\Agents;

use Laravel\Ai\Attributes\MaxTokens;
use Laravel\Ai\Attributes\Temperature;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Promptable;

#[MaxTokens(2048)]
#[Temperature(0.7)]
class ContentGenerator implements Agent
{
    use Promptable;

    public function instructions(): string
    {
        return 'You are an expert copywriter and content strategist. 
Generate high-quality, engaging, and well-structured text for a website.
Provide output in formatted Markdown unless otherwise specified.
Ensure the tone is appropriate for the context requested by the user.';
    }
}
