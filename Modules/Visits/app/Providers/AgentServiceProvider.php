<?php

namespace Modules\Visits\Providers;

use Modules\Visits\Agent;
use Illuminate\Support\ServiceProvider;

class AgentServiceProvider extends ServiceProvider
{


    protected $defer = true;
    
    /**
     * Register the service provider.
     */
    public function register(): void
    {

        $this->app->singleton('agent', function ($app) {
            return new Agent($app['request']->server());
        });

        $this->app->alias('agent', Agent::class);
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return ['agent', Agent::class];
    }
}
