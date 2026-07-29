<template>
    <UModal v-model:open="isOpenModel" :title="title" :description="description" class="z-50 backdrop-blur-sm">
        <template #content>
            <VisuallyHidden>
                <DialogTitle>{{ title }}</DialogTitle>
                <DialogDescription>{{ description }}</DialogDescription>
            </VisuallyHidden>
            <UCard class="border border-neutral-800 shadow-2xl rounded-xl overflow-hidden bg-neutral-900/95">
                <template #header>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary-500/20 text-primary-400 ring-1 ring-primary-500/30">
                            <UIcon name="ph:magic-wand-duotone" class="w-5 h-5 animate-pulse" />
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white bg-clip-text text-transparent bg-gradient-to-r from-primary-400 to-indigo-400">{{ title }}</h3>
                            <p class="text-xs text-neutral-400 mt-0.5">Powered by advanced LLM models</p>
                        </div>
                    </div>
                </template>

                <div class="space-y-4">
                    <p class="text-sm text-neutral-300 leading-relaxed">
                        {{ description }}
                    </p>

                    <!-- Suggestions / Quick Prompts -->
                    <div v-if="suggestions && suggestions.length > 0 && !isLoading && !jobResult" class="space-y-2">
                        <span class="text-xs font-medium text-neutral-400 uppercase tracking-wider">Quick Prompts</span>
                        <div class="flex flex-wrap gap-2">
                            <UButton
                                v-for="(suggestion, idx) in suggestions"
                                :key="idx"
                                variant="subtle"
                                color="neutral"
                                size="xs"
                                class="text-left"
                                @click="() => { promptText = suggestion }"
                            >
                                {{ suggestion }}
                            </UButton>
                        </div>
                    </div>

                    <!-- Prompt Input -->
                    <div v-if="!isLoading && !jobResult" class="relative group">
                        <div class="absolute -inset-0.5 bg-gradient-to-r from-primary-500 to-indigo-500 rounded-lg blur opacity-20 group-focus-within:opacity-40 transition duration-500"></div>
                        <UTextarea
                            v-model="promptText"
                            :disabled="isLoading"
                            color="primary"
                            variant="outline"
                            :placeholder="placeholder"
                            autoresize
                            class="relative w-full"
                        />
                    </div>

                    <!-- Live Progress & Status Bar -->
                    <div v-if="isLoading || error" class="p-3 bg-neutral-800/80 rounded-lg border border-neutral-700 space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-medium capitalize text-neutral-300 flex items-center gap-1.5">
                                <UIcon v-if="isLoading" name="ph:spinner" class="animate-spin text-primary-400" />
                                <UIcon v-else-if="error" name="ph:warning-circle" class="text-error-400" />
                                Status: {{ isLoading ? 'generating' : 'failed' }}
                            </span>
                            <span class="font-bold text-primary-400">{{ progress }}%</span>
                        </div>
                        <div class="w-full h-2 bg-neutral-700 rounded-full overflow-hidden">
                            <div
                                class="h-full bg-gradient-to-r from-primary-500 to-indigo-500 transition-all duration-300 ease-out"
                                :style="{ width: progress + '%' }"
                            ></div>
                        </div>
                        <div v-if="isLoading && job?.id" class="flex justify-start">
                            <UButton variant="link" color="error" size="xs" class="p-0 text-rose-400 hover:text-rose-300 underline" @click="handleCancel">
                                Cancel Job
                            </UButton>
                        </div>
                        <p v-if="error" class="text-xs text-error-400 font-mono mt-1">
                            Error: {{ error }}
                        </p>
                    </div>

                    <!-- Result Preview Display -->
                    <div v-if="jobResult && !isLoading" class="space-y-2">
                        <div class="flex items-center justify-between text-xs text-neutral-400">
                            <span class="flex items-center gap-1.5 text-emerald-400 font-semibold">
                                <UIcon name="ph:check-circle" class="w-4 h-4 text-emerald-400" />
                                Generated Result Ready
                            </span>
                            <UButton variant="ghost" color="neutral" size="xs" @click.prevent="() => { jobResult = null }">New Prompt</UButton>
                        </div>
                        <pre class="p-3 rounded-lg bg-neutral-950 border border-neutral-800 text-xs font-mono text-neutral-300 overflow-x-auto max-h-48 whitespace-pre-wrap">{{ JSON.stringify(jobResult, null, 2) }}</pre>
                    </div>
                </div>

                <template #footer>
                    <div class="flex justify-end gap-2">
                        <UButton color="neutral" variant="ghost" :disabled="isLoading" @click.prevent="closeModal">Cancel</UButton>
                        <UButton v-if="error" color="warning" variant="subtle" @click="handleRetry">Retry Job</UButton>
                        <UButton v-if="jobResult" color="success" @click="applyResult">Apply Result</UButton>
                        <UButton v-else color="primary" :loading="isLoading" :disabled="isLoading || !promptText" @click="handleSubmit">Generate</UButton>
                    </div>
                </template>
            </UCard>
        </template>
    </UModal>
</template>

<script setup lang="ts">
import { ref, watch, computed } from 'vue';
import { DialogTitle, DialogDescription, VisuallyHidden } from 'reka-ui';
import { useAiJob, extractResultPayload } from '../composables/useAiJob';

const props = withDefaults(
    defineProps<{
        isOpen: boolean;
        title?: string;
        description?: string;
        endpoint: string;
        placeholder?: string;
        type?: string;
        isAsync?: boolean;
        suggestions?: string[];
    }>(),
    {
        title: 'AI Generation Assistant',
        description: 'Describe what you want to create and let AI generate structured content.',
        placeholder: 'e.g. Create a landing page hero section with dark modern aesthetics...',
        isAsync: false,
        suggestions: () => [],
    }
);

const emit = defineEmits<{
    (e: 'update:isOpen', value: boolean): void;
    (e: 'success', payload: any): void;
    (e: 'error', errorMsg: string): void;
}>();

const promptText = ref<string>('');
const jobResult = ref<any>(null);

const { job, isLoading, progress, error, dispatchJob, retryJob, cancelJob } = useAiJob();

const isOpenModel = computed({
    get: () => props.isOpen,
    set: (value) => emit('update:isOpen', value)
});

watch(
    () => props.isOpen,
    (val) => {
        if (!val) {
            promptText.value = '';
            jobResult.value = null;
        }
    }
);

watch(
    job,
    (newJob) => {
        if (newJob && newJob.status === 'completed') {
            const payload = extractResultPayload(newJob.result) || extractResultPayload(newJob);
            if (payload) {
                jobResult.value = payload;
                emit('success', payload);
            }
        }
    },
    { deep: true, immediate: true }
);

const closeModal = () => {
    emit('update:isOpen', false);
};

const handleSubmit = async () => {
    if (!promptText.value.trim() || isLoading.value) return;
    try {
        await dispatchJob(
            props.endpoint,
            promptText.value.trim(),
            { type: props.type, isAsync: props.isAsync }
        );
    } catch (e: any) {
        emit('error', e.message || 'Failed to dispatch AI job');
    }
};

const handleRetry = async () => {
    if (job.value?.id) {
        await retryJob(job.value.id);
    }
};

const handleCancel = async () => {
    if (job.value?.id) {
        await cancelJob(job.value.id);
    }
};

const applyResult = () => {
    if (jobResult.value) {
        emit('success', jobResult.value);
        closeModal();
    }
};
</script>
