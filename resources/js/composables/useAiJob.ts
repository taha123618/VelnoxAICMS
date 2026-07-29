import { ref, onUnmounted } from 'vue';

/** Read a cookie value by name (used to forward XSRF-TOKEN for Sanctum SPA auth). */
function getCookie(name: string): string {
    const match = document.cookie.match(new RegExp('(?:^|; )' + name.replace(/([\.$?*|{}()\[\]\\\/+^])/g, '\\$1') + '=([^;]*)'));
    return match ? decodeURIComponent(match[1]) : '';
}

/** Build headers with CSRF token and optional JSON content-type. */
function apiHeaders(extra: Record<string, string> = {}): Record<string, string> {
    return {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-XSRF-TOKEN': getCookie('XSRF-TOKEN'),
        ...extra,
    };
}

export interface AiJob {
    id: string;
    user_id: number | string | null;
    type: string;
    prompt: string;
    status: 'queued' | 'processing' | 'completed' | 'failed' | 'cancelled';
    progress: number;
    result: Record<string, any> | null;
    error: string | null;
    created_at?: string;
    updated_at?: string;
}

export function extractResultPayload(data: any): any {
    if (!data) return null;
    if (data.result) return data.result;
    if (data.listing) return { listing: data.listing };
    if (data.article) return { article: data.article };
    if (data.workflow) return { workflow: data.workflow };
    if (data.form) return { form: data.form };
    if (data.testimonial) return { testimonial: data.testimonial };
    if (data.meta) return { meta: data.meta };
    if (data.elements) return { elements: data.elements };
    if (data.taxonomy) return { taxonomy: data.taxonomy };
    if (data.automation) return { automation: data.automation };
    return data;
}

export function useAiJob() {
    const job = ref<AiJob | null>(null);
    const isLoading = ref<boolean>(false);
    const progress = ref<number>(0);
    const error = ref<string | null>(null);
    const statusMessage = ref<string | null>(null);

    let activeEchoChannel: string | null = null;
    let fallbackTimer: ReturnType<typeof setTimeout> | null = null;

    const cleanupWebSocket = () => {
        if (activeEchoChannel && (window as any).Echo) {
            try {
                (window as any).Echo.leave(activeEchoChannel);
            } catch {
                // Ignore channel cleanup errors
            }
            activeEchoChannel = null;
        }
        if (fallbackTimer) {
            clearTimeout(fallbackTimer);
            fallbackTimer = null;
        }
    };

    /**
     * Perform a SINGLE API request to fetch the final generated data of the job.
     */
    const fetchSingleJobResult = async (jobId: string): Promise<AiJob | null> => {
        try {
            const response = await fetch(`/api/ai/jobs/${jobId}`, {
                credentials: 'include',
                headers: apiHeaders(),
            });

            if (!response.ok) {
                throw new Error('Failed to fetch AI job result');
            }

            const data = await response.json();
            const fetchedJob = data.job as AiJob;
            job.value = fetchedJob;
            progress.value = fetchedJob?.progress || 100;

            if (fetchedJob?.status === 'completed') {
                isLoading.value = false;
                error.value = null;
                statusMessage.value = 'Generation completed';
            } else if (fetchedJob?.status === 'failed' || fetchedJob?.status === 'cancelled') {
                isLoading.value = false;
                error.value = fetchedJob?.error || 'AI Generation failed';
                statusMessage.value = 'Generation failed';
            }

            return fetchedJob;
        } catch (e: any) {
            isLoading.value = false;
            error.value = e.message || 'Error retrieving job result';
            return null;
        } finally {
            cleanupWebSocket();
        }
    };

    /**
     * Dispatch AI generation request.
     * Supports both instant sync execution and real-time WebSocket queued job execution.
     * Guarantees that AI generated data is extracted and displayed on the UI.
     */
    const dispatchJob = async (
        endpoint: string,
        prompt: string,
        extraData: Record<string, any> = {}
    ) => {
        isLoading.value = true;
        progress.value = 10;
        error.value = null;
        statusMessage.value = 'Generation in progress...';
        job.value = null;
        cleanupWebSocket();

        const isAsyncMode = extraData.isAsync ?? false;
        const { isAsync, ...payloadExtra } = extraData;

        try {
            const response = await fetch(endpoint, {
                method: 'POST',
                credentials: 'include',
                headers: apiHeaders(),
                body: JSON.stringify({
                    prompt,
                    async: isAsyncMode,
                    ...payloadExtra,
                }),
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Failed to process AI generation request');
            }

            // Direct Sync or Payload Response Check
            const hasDirectPayload = Boolean(
                data.result ||
                data.listing ||
                data.article ||
                data.workflow ||
                data.form ||
                data.testimonial ||
                data.meta ||
                data.elements ||
                data.taxonomy ||
                data.automation
            );

            if (!isAsyncMode || hasDirectPayload || !data.job) {
                const resultPayload = extractResultPayload(data);
                const completedJob: AiJob = {
                    id: 'sync_' + Date.now(),
                    user_id: null,
                    type: payloadExtra.type || 'direct',
                    prompt,
                    status: 'completed',
                    progress: 100,
                    result: resultPayload,
                    error: null,
                };
                job.value = completedJob;
                progress.value = 100;
                isLoading.value = false;
                statusMessage.value = 'Generation completed';
                return completedJob;
            }

            // Async Queued Mode (HTTP 202)
            const queuedJob = data.job as AiJob;
            job.value = queuedJob;
            progress.value = queuedJob?.progress || 15;

            if (queuedJob?.status === 'completed' || queuedJob?.result) {
                isLoading.value = false;
                progress.value = 100;
                statusMessage.value = 'Generation completed';
                return queuedJob;
            }

            if (queuedJob?.id) {
                const channelName = `ai-jobs.${queuedJob.id}`;
                activeEchoChannel = channelName;

                // Subscribe to real-time WebSocket completion event via Laravel Echo / Reverb
                if ((window as any).Echo) {
                    (window as any).Echo.channel(channelName)
                        .listen('.AiJobStatusUpdated', async (e: { status: string; progress?: number; error?: string; result?: any }) => {
                            if (e.progress) {
                                progress.value = e.progress;
                            }

                            if (e.status === 'completed') {
                                if (e.result) {
                                    const doneJob: AiJob = {
                                        id: queuedJob.id,
                                        user_id: queuedJob.user_id,
                                        type: queuedJob.type,
                                        prompt: queuedJob.prompt,
                                        status: 'completed',
                                        progress: 100,
                                        result: extractResultPayload(e.result),
                                        error: null,
                                    };
                                    job.value = doneJob;
                                    progress.value = 100;
                                    isLoading.value = false;
                                    statusMessage.value = 'Generation completed';
                                    cleanupWebSocket();
                                } else {
                                    await fetchSingleJobResult(queuedJob.id);
                                }
                            } else if (e.status === 'failed' || e.status === 'cancelled') {
                                isLoading.value = false;
                                error.value = e.error || 'AI Job failed';
                                statusMessage.value = 'Generation failed';
                                cleanupWebSocket();
                            }
                        });
                }

                // Check once after 3s to guarantee response if Reverb WebSockets aren't active in dev
                fallbackTimer = setTimeout(async () => {
                    if (isLoading.value && job.value?.status !== 'completed') {
                        await fetchSingleJobResult(queuedJob.id);
                    }
                }, 3000);
            }

            return queuedJob;
        } catch (e: any) {
            isLoading.value = false;
            error.value = e.message || 'An unexpected error occurred';
            statusMessage.value = 'Generation failed';
            throw e;
        }
    };

    const retryJob = async (jobId: string) => {
        isLoading.value = true;
        error.value = null;
        statusMessage.value = 'Retrying generation...';
        cleanupWebSocket();

        try {
            const response = await fetch(`/api/ai/jobs/${jobId}/retry`, {
                method: 'POST',
                credentials: 'include',
                headers: apiHeaders(),
            });
            const data = await response.json();
            job.value = data.job;

            if ((window as any).Echo && data.job?.id) {
                const channelName = `ai-jobs.${data.job.id}`;
                activeEchoChannel = channelName;
                (window as any).Echo.channel(channelName)
                    .listen('.AiJobStatusUpdated', async (e: { status: string; result?: any }) => {
                        if (e.status === 'completed') {
                            if (e.result) {
                                const doneJob: AiJob = {
                                    id: data.job.id,
                                    user_id: data.job.user_id,
                                    type: data.job.type,
                                    prompt: data.job.prompt,
                                    status: 'completed',
                                    progress: 100,
                                    result: extractResultPayload(e.result),
                                    error: null,
                                };
                                job.value = doneJob;
                                progress.value = 100;
                                isLoading.value = false;
                                statusMessage.value = 'Generation completed';
                                cleanupWebSocket();
                            } else {
                                await fetchSingleJobResult(data.job.id);
                            }
                        } else if (e.status === 'failed' || e.status === 'cancelled') {
                            isLoading.value = false;
                            error.value = 'Retry failed';
                            cleanupWebSocket();
                        }
                    });
            }

            return data.job;
        } catch (e: any) {
            isLoading.value = false;
            error.value = e.message || 'Failed to retry job';
        }
    };

    const cancelJob = async (jobId: string) => {
        try {
            const response = await fetch(`/api/ai/jobs/${jobId}/cancel`, {
                method: 'POST',
                credentials: 'include',
                headers: apiHeaders(),
            });
            const data = await response.json();
            job.value = data.job;
            isLoading.value = false;
            statusMessage.value = 'Generation cancelled';
            cleanupWebSocket();
            return data.job;
        } catch (e: any) {
            error.value = e.message || 'Failed to cancel job';
        }
    };

    onUnmounted(() => {
        cleanupWebSocket();
    });

    return {
        job,
        isLoading,
        progress,
        error,
        statusMessage,
        dispatchJob,
        retryJob,
        cancelJob,
    };
}
