<?php

namespace Modules\Marketplace\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Modules\Marketplace\Listeners\DispatchWebhooksListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        // Intercept Eloquent events to dispatch webhooks.
        Event::listen('eloquent.*', [DispatchWebhooksListener::class, 'handle']);
        Event::listen('ziora.*', [DispatchWebhooksListener::class, 'handle']);
    }
}
