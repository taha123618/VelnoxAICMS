<?php

namespace Modules\Marketplace\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Modules\Marketplace\Models\Webhook;

class DispatchWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Webhook $webhook,
        public string $event,
        public array $payload
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $signature = hash_hmac('sha256', json_encode($this->payload), $this->webhook->secret ?? '');

        try {
            Http::withHeaders([
                'X-VelnoxAI-Event' => $this->event,
                'X-VelnoxAI-Signature' => $signature,
            ])
                ->timeout(10)
                ->post($this->webhook->url, $this->payload);
        } catch (\Exception $e) {
            \Log::error("Webhook delivery failed for URL {$this->webhook->url}: ".$e->getMessage());
        }
    }
}
