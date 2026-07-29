<script setup lang="ts">
import { ref } from 'vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import AdminLayout from '@modules/Dashboard/resources/layouts/AdminLayout.vue';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import type { BreadcrumbItem } from '@nuxt/ui';
import type { SharedData } from '@/types';

dayjs.extend(relativeTime);

const props = withDefaults(defineProps<{
    tokens?: any[] | null;
}>(), {
    tokens: () => [],
});

const page = usePage<SharedData>();
const showNewToken = ref(false);
const newTokenSecret = ref<string | null>(null);

const breadcrumbs: BreadcrumbItem[] = [
    { label: 'API Tokens', icon: 'ph:key' },
];

const form = useForm({
    name: '',
});

function createToken() {
    form.post(route('api-tokens.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('name');
            showNewToken.value = false;
            // Read the token secret from shared flash props after the Inertia redirect
            const flash = (usePage().props as any).flash;
            newTokenSecret.value = flash?.token ?? null;
        },
    });
}

function revokeToken(id: number) {
    if (confirm('Are you sure? Revoking this token will immediately invalidate all requests using it.')) {
        useForm({}).delete(route('api-tokens.destroy', id), {
            preserveScroll: true,
        });
    }
}

function formatDate(dateString: string) {
    return dayjs(dateString).format('MMM D, YYYY h:mm A');
}

function formatRelative(dateString: string | null) {
    if (!dateString) return 'Never';
    return dayjs(dateString).fromNow();
}

function copyToClipboard(text: string) {
    navigator.clipboard.writeText(text).then(() => {
        useToast().add({ title: 'Copied!', description: 'Token copied to clipboard.', color: 'success' });
    });
}

function parseAbilities(abilities: string | string[] | null): string[] {
    if (!abilities) return ['*'];
    if (Array.isArray(abilities)) return abilities;
    try {
        const parsed = JSON.parse(abilities);
        return Array.isArray(parsed) ? parsed : ['*'];
    } catch {
        return ['*'];
    }
}
</script>

<template>
    <Head title="API Tokens" />

    <AdminLayout :breadcrumbs="breadcrumbs">
        <div class="px-4 sm:px-6 lg:px-8 py-8 space-y-6">

            <!-- Header -->
            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-white tracking-wide">API Tokens</h1>
                    <p class="mt-1 text-sm text-neutral-400">
                        Create and manage personal API tokens for programmatic access to the ZioraCMS API.
                    </p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <UButton
                        color="primary"
                        icon="ph:plus-circle"
                        @click.prevent="() => { showNewToken = true; }"
                    >
                        Create Token
                    </UButton>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="p-5 rounded-2xl bg-neutral-900/80 border border-neutral-800 space-y-2">
                    <div class="flex items-center justify-between text-xs text-neutral-400">
                        <span>Active Tokens</span>
                        <UIcon name="ph:key" class="text-indigo-400 w-5 h-5" />
                    </div>
                    <div class="text-2xl font-bold text-white">{{ tokens?.length ?? 0 }}</div>
                </div>
                <div class="p-5 rounded-2xl bg-neutral-900/80 border border-neutral-800 space-y-2">
                    <div class="flex items-center justify-between text-xs text-neutral-400">
                        <span>Last Used</span>
                        <UIcon name="ph:clock" class="text-amber-400 w-5 h-5" />
                    </div>
                    <div class="text-lg font-semibold text-white">
                        {{ tokens?.length ? formatRelative(tokens[0]?.last_used_at) : 'N/A' }}
                    </div>
                </div>
                <div class="p-5 rounded-2xl bg-neutral-900/80 border border-neutral-800 space-y-2">
                    <div class="flex items-center justify-between text-xs text-neutral-400">
                        <span>API Security</span>
                        <UIcon name="ph:shield-check" class="text-emerald-400 w-5 h-5" />
                    </div>
                    <div class="text-sm font-semibold text-emerald-400">Sanctum Protected</div>
                </div>
            </div>

            <!-- New Token Secret Banner -->
            <div v-if="newTokenSecret" class="p-4 rounded-xl border border-amber-500/30 bg-amber-500/10 flex items-start gap-4">
                <UIcon name="ph:warning-circle" class="text-amber-400 w-5 h-5 flex-shrink-0 mt-0.5" />
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-amber-300">Save your new token now. It won't be shown again!</p>
                    <code class="block mt-2 text-xs bg-neutral-950 rounded-lg p-3 text-emerald-400 break-all font-mono">
                        {{ newTokenSecret }}
                    </code>
                </div>
                <UButton size="xs" color="neutral" variant="ghost" icon="ph:copy" @click="copyToClipboard(newTokenSecret!)" />
                <UButton size="xs" color="neutral" variant="ghost" icon="ph:x" @click="() => { newTokenSecret = null; }" />
            </div>

            <!-- Create Token Form -->
            <UCard v-if="showNewToken" class="border border-primary-800 bg-neutral-900/60">
                <template #header>
                    <h3 class="text-sm font-semibold text-white flex items-center gap-2">
                        <UIcon name="ph:plus-circle" class="text-primary-400" />
                        New API Token
                    </h3>
                </template>
                <div class="flex gap-3 items-end">
                    <UFormField label="Token Name" class="flex-1" :error="form.errors.name">
                        <UInput
                            v-model="form.name"
                            placeholder="e.g. Production Deploy Key"
                            class="w-full"
                            @keyup.enter="createToken"
                        />
                    </UFormField>
                    <UButton color="primary" :loading="form.processing" @click="createToken">
                        Generate
                    </UButton>
                    <UButton color="neutral" variant="ghost" @click.prevent="() => { showNewToken = false; }">
                        Cancel
                    </UButton>
                </div>
            </UCard>

            <!-- Tokens List -->
            <UCard class="border border-neutral-800 bg-neutral-900/60 shadow-xl">
                <template #header>
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-semibold text-white flex items-center gap-2">
                            <UIcon name="ph:key" class="text-indigo-400" />
                            Your Tokens
                        </h3>
                        <UBadge color="neutral" variant="subtle" size="xs">
                            {{ (tokens ?? []).length }} token{{ (tokens ?? []).length !== 1 ? 's' : '' }}
                        </UBadge>
                    </div>
                </template>

                <div v-if="!tokens || tokens.length === 0" class="text-center py-16 text-neutral-500">
                    <UIcon name="ph:key" class="w-12 h-12 mx-auto mb-3 opacity-20" />
                    <p class="text-sm font-medium">No API tokens yet.</p>
                    <p class="text-xs mt-1 text-neutral-600">Create a token above to get started.</p>
                </div>

                <div v-else class="divide-y divide-neutral-800/50">
                    <div
                        v-for="token in (tokens ?? [])"
                        :key="token.id"
                        class="grid grid-cols-[auto_1fr_auto] gap-4 items-center px-4 py-4 hover:bg-white/[0.02] transition-colors group"
                    >
                        <!-- Icon -->
                        <div class="w-10 h-10 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center flex-shrink-0">
                            <UIcon name="ph:key" class="text-indigo-400 w-4 h-4" />
                        </div>

                        <!-- Info -->
                        <div class="min-w-0 space-y-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-sm font-semibold text-white">{{ token.name }}</span>
                                <UBadge
                                    v-for="ability in parseAbilities(token.abilities)"
                                    :key="ability"
                                    size="xs"
                                    variant="subtle"
                                    :color="ability === '*' ? 'warning' : 'primary'"
                                >
                                    {{ ability === '*' ? 'Full Access' : ability }}
                                </UBadge>
                            </div>
                            <div class="flex items-center gap-3 text-xs flex-wrap">
                                <span class="flex items-center gap-1 text-neutral-500">
                                    <UIcon name="ph:calendar-blank" class="w-3 h-3" />
                                    Created {{ formatDate(token.created_at) }}
                                </span>
                                <span class="text-neutral-700">•</span>
                                <span
                                    class="flex items-center gap-1"
                                    :class="token.last_used_at ? 'text-emerald-500' : 'text-neutral-500'"
                                >
                                    <UIcon name="ph:clock" class="w-3 h-3" />
                                    {{ token.last_used_at ? 'Last used ' + formatRelative(token.last_used_at) : 'Never used' }}
                                </span>
                            </div>
                        </div>

                        <!-- Actions (reveal on hover) -->
                        <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <UButton
                                size="sm"
                                color="error"
                                variant="soft"
                                icon="ph:trash"
                                @click="revokeToken(token.id)"
                            >
                                Revoke
                            </UButton>
                        </div>
                    </div>
                </div>
            </UCard>

        </div>
    </AdminLayout>
</template>

<style scoped></style>