<?php

namespace Modules\Automation\Observers;

use Modules\Automation\Models\WebhookObserver;

class WebhookObserverObserver
{
    /**
     * Handle the WebhookObserver "created" event.
     */
    public function created(WebhookObserver $webhookobserver): void {}

    /**
     * Handle the WebhookObserver "updated" event.
     */
    public function updated(WebhookObserver $webhookobserver): void {}

    /**
     * Handle the WebhookObserver "deleted" event.
     */
    public function deleted(WebhookObserver $webhookobserver): void {}

    /**
     * Handle the WebhookObserver "restored" event.
     */
    public function restored(WebhookObserver $webhookobserver): void {}

    /**
     * Handle the WebhookObserver "force deleted" event.
     */
    public function forceDeleted(WebhookObserver $webhookobserver): void {}
}
