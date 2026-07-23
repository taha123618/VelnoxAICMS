<?php

namespace Modules\Marketplace\Listeners;

use Illuminate\Support\Facades\Event;
use Modules\Marketplace\Jobs\DispatchWebhook;
use Modules\Marketplace\Models\Webhook;

class DispatchWebhooksListener
{
    /**
     * Handle the event.
     */
    public function handle(string $eventName, array $payload): void
    {
        // Prevent infinite loops and issues during Eloquent model booting
        if (str_starts_with($eventName, 'eloquent.booting') || 
            str_starts_with($eventName, 'eloquent.booted') ||
            str_starts_with($eventName, 'eloquent.retrieved') ||
            str_starts_with($eventName, 'eloquent.creating') ||
            str_starts_with($eventName, 'eloquent.updating') ||
            str_starts_with($eventName, 'eloquent.saving') ||
            str_starts_with($eventName, 'eloquent.deleting') ||
            str_starts_with($eventName, 'eloquent.restoring')) {
            return;
        }

        // Check if there are active webhooks
        // To avoid querying DB on every single Laravel event, this should be heavily cached in production.
        // For now, we will query the DB for simplicity and cache it for 1 minute.
        $webhooks = \Cache::remember('active_webhooks', 60, function () {
            if (\Schema::hasTable('webhooks')) {
                return Webhook::where('is_active', true)->get();
            }

            return collect();
        });

        foreach ($webhooks as $webhook) {
            $subscribedEvents = $webhook->events ?? [];
            if (in_array('*', $subscribedEvents) || in_array($eventName, $subscribedEvents)) {
                DispatchWebhook::dispatch($webhook, $eventName, $payload);
            }
        }
    }
}
