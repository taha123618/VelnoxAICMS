<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AdminLayout from '@modules/Dashboard/resources/layouts/AdminLayout.vue';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import type { BreadcrumbItem } from '@nuxt/ui';

dayjs.extend(relativeTime);

defineProps<{
    logs: {
        data: any[];
        links: any[];
        current_page: number;
        last_page: number;
        total: number;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { label: 'Audit Log', icon: 'ph:clock-counter-clockwise' },
];

function formatDate(dateString: string) {
    return dayjs(dateString).format('MMM D, YYYY h:mm A');
}

function formatRelative(dateString: string) {
    return dayjs(dateString).fromNow();
}

function formatDescription(desc: string) {
    return desc.charAt(0).toUpperCase() + desc.slice(1);
}

function eventColor(event: string) {
    if (!event) return 'neutral';
    const map: Record<string, string> = {
        created: 'success',
        updated: 'warning',
        deleted: 'error',
        restored: 'info',
    };
    return map[event.toLowerCase()] ?? 'neutral';
}

function eventIcon(event: string) {
    const map: Record<string, string> = {
        created: 'ph:plus-circle',
        updated: 'ph:pencil-simple',
        deleted: 'ph:trash',
        restored: 'ph:arrow-counter-clockwise',
    };
    return map[(event ?? '').toLowerCase()] ?? 'ph:activity';
}
</script>

<template>
    <Head title="Audit Log" />
    <AdminLayout :breadcrumbs="breadcrumbs">
        <div class="px-4 sm:px-6 lg:px-8 py-8 space-y-6">

            <!-- Header -->
            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-white tracking-wide">Audit Log</h1>
                    <p class="mt-1 text-sm text-neutral-400">
                        Full history of system actions performed by users and automations.
                    </p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <UBadge color="neutral" variant="soft" size="lg">
                        <UIcon name="ph:clock-counter-clockwise" class="mr-1" />
                        {{ logs.total ?? logs.data.length }} Events Recorded
                    </UBadge>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="p-5 rounded-2xl bg-neutral-900/80 border border-neutral-800 space-y-1">
                    <div class="text-xs text-neutral-400 flex items-center gap-1">
                        <UIcon name="ph:plus-circle" class="text-emerald-400" /> Created
                    </div>
                    <div class="text-xl font-bold text-white">
                        {{ logs.data.filter(l => l.event?.toLowerCase() === 'created').length }}
                    </div>
                </div>
                <div class="p-5 rounded-2xl bg-neutral-900/80 border border-neutral-800 space-y-1">
                    <div class="text-xs text-neutral-400 flex items-center gap-1">
                        <UIcon name="ph:pencil-simple" class="text-amber-400" /> Updated
                    </div>
                    <div class="text-xl font-bold text-white">
                        {{ logs.data.filter(l => l.event?.toLowerCase() === 'updated').length }}
                    </div>
                </div>
                <div class="p-5 rounded-2xl bg-neutral-900/80 border border-neutral-800 space-y-1">
                    <div class="text-xs text-neutral-400 flex items-center gap-1">
                        <UIcon name="ph:trash" class="text-red-400" /> Deleted
                    </div>
                    <div class="text-xl font-bold text-white">
                        {{ logs.data.filter(l => l.event?.toLowerCase() === 'deleted').length }}
                    </div>
                </div>
                <div class="p-5 rounded-2xl bg-neutral-900/80 border border-neutral-800 space-y-1">
                    <div class="text-xs text-neutral-400 flex items-center gap-1">
                        <UIcon name="ph:users" class="text-indigo-400" /> Users Active
                    </div>
                    <div class="text-xl font-bold text-white">
                        {{ new Set(logs.data.map(l => l.causer_id).filter(Boolean)).size }}
                    </div>
                </div>
            </div>

            <!-- Log Timeline -->
            <UCard class="border border-neutral-800 bg-neutral-900/60 shadow-xl">
                <template #header>
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-semibold text-white flex items-center gap-2">
                            <UIcon name="ph:list-bullets" class="text-indigo-400" />
                            Activity Timeline
                        </h3>
                        <UBadge color="primary" variant="subtle" size="sm">{{ logs.data.length }} entries</UBadge>
                    </div>
                </template>

                <div v-if="logs.data.length === 0" class="text-center py-16 text-neutral-500">
                    <UIcon name="ph:clock-counter-clockwise" class="w-12 h-12 mx-auto mb-3 opacity-40" />
                    <p class="text-sm">No audit log entries found.</p>
                </div>

                <div v-else class="divide-y divide-neutral-800/60">
                    <div
                        v-for="log in logs.data"
                        :key="log.id"
                        class="flex items-start gap-4 p-4 hover:bg-neutral-800/30 transition-colors"
                    >
                        <!-- Event Icon -->
                        <div class="flex-shrink-0 mt-0.5">
                            <div class="w-8 h-8 rounded-full bg-neutral-800 flex items-center justify-center">
                                <UIcon :name="eventIcon(log.event)" class="w-4 h-4 text-neutral-300" />
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <UBadge :color="(eventColor(log.event) as any)" variant="subtle" size="xs" class="capitalize">
                                    {{ log.event ?? 'unknown' }}
                                </UBadge>
                                <span class="text-sm text-white font-medium truncate">
                                    {{ formatDescription(log.description ?? '') }}
                                </span>
                            </div>
                            <div class="flex flex-wrap items-center gap-3 text-xs text-neutral-500">
                                <span v-if="log.causer" class="flex items-center gap-1">
                                    <UIcon name="ph:user" />
                                    {{ log.causer?.name ?? 'System' }}
                                </span>
                                <span v-if="log.subject_type" class="flex items-center gap-1">
                                    <UIcon name="ph:cube" />
                                    {{ log.subject_type?.split('\\').pop() }} #{{ log.subject_id }}
                                </span>
                                <span class="flex items-center gap-1" :title="formatDate(log.created_at)">
                                    <UIcon name="ph:clock" />
                                    {{ formatRelative(log.created_at) }}
                                </span>
                            </div>
                        </div>

                        <!-- Date -->
                        <div class="hidden sm:block text-right text-xs text-neutral-500 flex-shrink-0">
                            {{ formatDate(log.created_at) }}
                        </div>
                    </div>
                </div>
            </UCard>

            <!-- Pagination links -->
            <div v-if="logs.links?.length" class="flex flex-wrap gap-2 justify-center">
                <template v-for="link in logs.links" :key="link.label">
                    <Link
                        v-if="link.url"
                        :href="link.url"
                        class="px-3 py-1.5 rounded-lg text-xs border transition-colors"
                        :class="link.active
                            ? 'bg-primary-500 border-primary-400 text-white'
                            : 'border-neutral-700 text-neutral-400 hover:bg-neutral-800'"
                        v-html="link.label"
                    />
                    <span
                        v-else
                        class="px-3 py-1.5 rounded-lg text-xs border border-neutral-800 text-neutral-600 cursor-not-allowed"
                        v-html="link.label"
                    />
                </template>
            </div>

        </div>
    </AdminLayout>
</template>

<style scoped></style>