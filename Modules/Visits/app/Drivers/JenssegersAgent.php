<?php

namespace Modules\Visits\Drivers;

use Illuminate\Http\Request;
use Modules\Visits\Agent;
use Modules\Visits\Contracts\UserAgentParser;

class JenssegersAgent implements UserAgentParser
{
    /**
     * Agent parser.
     */
    protected Agent $parser;

    /**
     * Parser constructor.
     */
    public function __construct(/**
     * Request container.
     */
        protected Request $request)
    {
        $this->parser = $this->initParser();
    }

    /**
     * Retrieve device's name.
     */
    public function device(): string
    {
        return $this->parser->device();
    }

    /**
     * Retrieve platform's name.
     */
    public function platform(): string
    {
        return $this->parser->platform();
    }

    /**
     * Retrieve browser's name.
     */
    public function browser(): string
    {
        return $this->parser->browser();
    }

    /**
     * Retrieve languages.
     */
    public function languages(): array
    {
        return $this->parser->languages();
    }

    /**
     * Initialize userAgent parser.
     */
    protected function initParser(): Agent
    {
        $agent = new Agent;
        $userAgent = $this->request->userAgent() ?? '';

        $agent->setUserAgent($userAgent);
        $agent->setHttpHeaders((array) $this->request->headers);

        return $agent;
    }
}
