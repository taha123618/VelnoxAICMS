<?php

namespace Modules\Visits\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Visits\Agent;

class AgentServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Register the service provider.
     */
    #[\Override]
    public function register(): void
    {

        $this->app->singleton('agent', fn ($app): Agent => new Agent($app['request']->server()));

        $this->app->alias('agent', Agent::class);
    }

    /**
     * Get the services provided by the provider.
     */
    #[\Override]
    public function provides(): array
    {
        return ['agent', Agent::class];
    }
}
