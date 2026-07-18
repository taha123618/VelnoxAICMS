<?php

namespace Modules\Automation\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Modules\Automation\Models\Webhook;
use Illuminate\Support\Facades\Log;

class FireWebhookJob implements ShouldQueue
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
        try {
            $headers = [
                'Content-Type' => 'application/json',
                'X-Ziora-Event' => $this->event,
            ];

            if ($this->webhook->secret) {
                $signature = hash_hmac('sha256', json_encode($this->payload), $this->webhook->secret);
                $headers['X-Ziora-Signature'] = $signature;
            }

            Http::withHeaders($headers)
                ->post($this->webhook->url, $this->payload);
        } catch (\Exception $e) {
            Log::error('Webhook firing failed: ' . $e->getMessage(), [
                'webhook_id' => $this->webhook->id,
                'event' => $this->event,
            ]);
        }
    }
}
