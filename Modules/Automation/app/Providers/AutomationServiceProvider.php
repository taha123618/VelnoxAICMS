<?php

namespace Modules\Automation\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Modules\Automation\Models\Webhook;
use Modules\Automation\Jobs\FireWebhookJob;
use Illuminate\Database\Eloquent\Model;

class AutomationServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
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
        Event::listen('eloquent.saved: *', function (string $eventName, array $data) {
            $this->dispatchWebhooks('entry.updated', $data[0] ?? null);
        });

        Event::listen('eloquent.created: *', function (string $eventName, array $data) {
            $this->dispatchWebhooks('entry.created', $data[0] ?? null);
        });

        Event::listen('eloquent.deleted: *', function (string $eventName, array $data) {
            $this->dispatchWebhooks('entry.deleted', $data[0] ?? null);
        });
    }

    protected function dispatchWebhooks(string $eventType, ?Model $model): void
    {
        if (!$model) return;

        // Optionally, restrict to certain models like Page, Post, Entry
        $class = get_class($model);
        if (str_contains($class, 'Webhook') || str_contains($class, 'Language') || str_contains($class, 'SeoMeta')) {
            return;
        }

        try {
            $webhooks = Webhook::where('is_active', true)
                ->whereJsonContains('events', $eventType)
                ->get();

            foreach ($webhooks as $webhook) {
                FireWebhookJob::dispatch($webhook, $eventType, $model->toArray());
            }
        } catch (\Exception $e) {
            // Table might not exist yet during migration
        }
    }
}
