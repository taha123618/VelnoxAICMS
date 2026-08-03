<?php

namespace Modules\Automation\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Modules\Automation\Jobs\FireWebhookJob;
use Modules\Automation\Models\Webhook;

class AutomationServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    #[\Override]
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(module_path('Automation', 'database/migrations'));
        $this->loadViewsFrom(module_path('Automation', 'resources/views'), 'Automation');

        // Listen for all Eloquent saved events
        Event::listen('eloquent.saved: *', function (string $eventName, array $data): void {
            $this->dispatchWebhooks('entry.updated', $data[0] ?? null);
        });

        Event::listen('eloquent.created: *', function (string $eventName, array $data): void {
            $this->dispatchWebhooks('entry.created', $data[0] ?? null);
        });

        Event::listen('eloquent.deleted: *', function (string $eventName, array $data): void {
            $this->dispatchWebhooks('entry.deleted', $data[0] ?? null);
        });
    }

    protected function dispatchWebhooks(string $eventType, ?Model $model): void
    {
        if (! $model instanceof Model) {
            return;
        }

        // Optionally, restrict to certain models like Page, Post, Entry
        $class = $model::class;
        if (str_contains($class, 'Webhook') || str_contains($class, 'Language') || str_contains($class, 'SeoMeta')) {
            return;
        }

        try {
            $webhooks = Webhook::where('is_active', true)
                ->whereJsonContains('events', $eventType)
                ->get();

            foreach ($webhooks as $webhook) {
                dispatch(new FireWebhookJob($webhook, $eventType, $model->toArray()));
            }
        } catch (\Exception) {
            // Table might not exist yet during migration
        }
    }
}
