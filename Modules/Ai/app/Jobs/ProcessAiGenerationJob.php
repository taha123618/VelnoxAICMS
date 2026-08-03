<?php

declare(strict_types=1);

namespace Modules\Ai\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Modules\Ai\Agents\PageSectionGenerator;
use Modules\Ai\Events\AiJobStatusUpdated;
use Modules\Ai\Models\AiGenerationJob;
use Modules\Ai\Notifications\AiJobCompletedNotification;
use Modules\Automation\Agents\AutomationRuleGenerator;
use Modules\Automation\Models\Webhook;
use Modules\Category\Agents\CategoryTaxonomyGenerator;
use Modules\Category\Models\Category;
use Modules\Content\Agents\ArticleGenerator;
use Modules\Content\Models\Collection;
use Modules\Content\Models\Entry;
use Modules\Forms\Agents\FormSchemaGenerator;
use Modules\Forms\Models\Form;
use Modules\Marketplace\Agents\MarketplaceGenerator;
use Modules\Seo\Agents\SeoMetadataGenerator;
use Modules\Testimonial\Agents\TestimonialGenerator;
use Modules\Testimonial\Models\Testimonial;
use Modules\Workflow\Agents\WorkflowGenerator;
use Throwable;

class ProcessAiGenerationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $backoff = 5;

    public int $timeout = 60;

    public function __construct(public int|string $jobId)
    {
        $this->onQueue('ai-generation');
    }

    public function handle(): void
    {
        $job = AiGenerationJob::find($this->jobId);

        if (! $job) {
            Log::error("AiGenerationJob not found: {$this->jobId}");

            return;
        }

        // Idempotency check
        if (in_array($job->status, [AiGenerationJob::STATUS_COMPLETED, AiGenerationJob::STATUS_CANCELLED])) {
            return;
        }

        try {
            // Step 1: Processing started
            $job->markAsProcessing(20);

            // Step 2: Invoke LLM Agent according to job type
            $result = match ($job->type) {
                'content', 'article' => $this->generateContent($job->prompt),
                'seo' => $this->generateSeo($job->prompt),
                'marketplace' => $this->generateMarketplace($job->prompt),
                'workflow' => $this->generateWorkflow($job->prompt),
                'category' => $this->generateCategory($job->prompt),
                'automation' => $this->generateAutomation($job->prompt),
                'testimonial' => $this->generateTestimonial($job->prompt),
                'form' => $this->generateForm($job->prompt),
                default => $this->generateSection($job->prompt),
            };

            // Step 3: Update progress to 80% and broadcast update
            $job->updateProgress(80);
            $this->safeBroadcast($job);

            // Step 4: Complete Job and broadcast terminal status
            $job->markAsCompleted($result);
            $this->safeBroadcast($job);

            // Send notification to user if attached
            if ($job->user && method_exists($job->user, 'notify')) {
                $job->user->notify(new AiJobCompletedNotification($job));
            }

        } catch (Throwable $e) {
            Log::error("AiGenerationJob failed: {$job->id}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $job->markAsFailed($e->getMessage());
            $this->safeBroadcast($job);

            if ($job->user && method_exists($job->user, 'notify')) {
                $job->user->notify(new AiJobCompletedNotification($job));
            }

            throw $e;
        }
    }

    protected function safeBroadcast(AiGenerationJob $aiGenerationJob): void
    {
        try {
            event(new AiJobStatusUpdated($aiGenerationJob));
        } catch (Throwable $e) {
            Log::warning("WebSocket broadcast failed for AiGenerationJob {$aiGenerationJob->id} (Reverb server down or unreachable): ".$e->getMessage());
        }
    }

    protected function generateSection(string $prompt): array
    {
        $pageSectionGenerator = new PageSectionGenerator;
        $agentResponse = $pageSectionGenerator->prompt($prompt);

        return [
            'elements' => $agentResponse['elements'] ?? [],
        ];
    }

    protected function generateContent(string $prompt): array
    {
        $articleGenerator = new ArticleGenerator;
        $agentResponse = $articleGenerator->prompt($prompt);

        if (! empty($agentResponse['title'])) {
            $collectionId = Collection::first()?->id;
            if ($collectionId) {
                Entry::create([
                    'collection_id' => $collectionId,
                    'user_id' => null,
                    'title' => $agentResponse['title'],
                    'slug' => Str::slug($agentResponse['title']),
                    'data' => [
                        'content' => $agentResponse['content'] ?? '',
                        'excerpt' => $agentResponse['excerpt'] ?? '',
                    ],
                    'status' => 'draft',
                ]);
            }
        }

        return [
            'article' => $agentResponse,
        ];
    }

    protected function generateSeo(string $prompt): array
    {
        $seoMetadataGenerator = new SeoMetadataGenerator;
        $agentResponse = $seoMetadataGenerator->prompt($prompt);

        return [
            'meta' => $agentResponse,
        ];
    }

    protected function generateMarketplace(string $prompt): array
    {
        $marketplaceGenerator = new MarketplaceGenerator;
        $agentResponse = $marketplaceGenerator->prompt($prompt);

        return [
            'listing' => $agentResponse,
        ];
    }

    protected function generateWorkflow(string $prompt): array
    {
        $workflowGenerator = new WorkflowGenerator;
        $agentResponse = $workflowGenerator->prompt($prompt);

        return [
            'workflow' => $agentResponse,
        ];
    }

    protected function generateCategory(string $prompt): array
    {
        $categoryTaxonomyGenerator = new CategoryTaxonomyGenerator;
        $agentResponse = $categoryTaxonomyGenerator->prompt($prompt);

        if (! empty($agentResponse['category_name']) || ! empty($agentResponse['name'])) {
            $name = $agentResponse['category_name'] ?? $agentResponse['name'] ?? 'AI Category';
            Category::create([
                'name' => $name,
                'slug' => Str::slug($name.'-'.time()),
            ]);
        }

        return [
            'taxonomy' => $agentResponse,
        ];
    }

    protected function generateAutomation(string $prompt): array
    {
        $automationRuleGenerator = new AutomationRuleGenerator;
        $agentResponse = $automationRuleGenerator->prompt($prompt);

        if (! empty($agentResponse['rule_name']) || ! empty($agentResponse['event_trigger'])) {
            Webhook::create([
                'name' => $agentResponse['rule_name'] ?? 'AI Automation Listener',
                'url' => 'https://api.myapp.com/webhooks/'.($agentResponse['event_trigger'] ?? 'automation'),
                'events' => [$agentResponse['event_trigger'] ?? 'entry.created'],
                'secret' => 'whsec_ai_'.Str::random(10),
                'is_active' => true,
            ]);
        }

        return [
            'automation' => $agentResponse,
        ];
    }

    protected function generateForm(string $prompt): array
    {
        $formSchemaGenerator = new FormSchemaGenerator;
        $agentResponse = $formSchemaGenerator->prompt($prompt);

        if (! empty($agentResponse['form_title']) || ! empty($agentResponse['title']) || ! empty($agentResponse['name'])) {
            $title = $agentResponse['form_title'] ?? $agentResponse['title'] ?? $agentResponse['name'] ?? 'AI Form';
            Form::create([
                'name' => $title,
                'slug' => Str::slug($title.'-'.time()),
                'description' => $agentResponse['description'] ?? 'Generated by AI assistant.',
                'schema' => $agentResponse['fields'] ?? $agentResponse['schema'] ?? [],
                'is_active' => true,
                'success_message' => 'Thank you for submitting the form.',
            ]);
        }

        return [
            'form' => $agentResponse,
        ];
    }

    protected function generateTestimonial(string $prompt): array
    {
        $testimonialGenerator = new TestimonialGenerator;
        $result = $testimonialGenerator->generate($prompt);

        if (! empty($result['testimonial'])) {
            $item = $result['testimonial'];
            Testimonial::create([
                'name' => $item['name'] ?? 'AI Customer',
                'title' => $item['title'] ?? $item['company'] ?? 'Verified Customer',
                'comment' => $item['comment'] ?? 'Outstanding experience!',
                'avatar' => $item['avatar'] ?? null,
                'published_at' => now(),
            ]);
        }

        return $result;
    }

    public function failed(Throwable $throwable): void
    {
        $job = AiGenerationJob::find($this->jobId);
        if ($job && $job->status !== AiGenerationJob::STATUS_COMPLETED) {
            $job->markAsFailed($throwable->getMessage());
            $this->safeBroadcast($job);

            if ($job->user && method_exists($job->user, 'notify')) {
                $job->user->notify(new AiJobCompletedNotification($job));
            }
        }
    }
}
