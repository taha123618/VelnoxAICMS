<?php

declare(strict_types=1);

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
    #[\Override]
    protected $listen = [];

    /**
     * Register any events for your application.
     */
    #[\Override]
    public function boot(): void
    {
        // Intercept Eloquent events to dispatch webhooks.
        Event::listen('eloquent.*', [DispatchWebhooksListener::class, 'handle']);
        Event::listen('VelnoxAI.*', [DispatchWebhooksListener::class, 'handle']);
    }
}
