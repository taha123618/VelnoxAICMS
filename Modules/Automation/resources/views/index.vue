<template>
    <AdminLayout :breadcrumbs="breadcrumbs">
        <Head title="Automation & Webhooks" />

        <div class="px-4 sm:px-6 lg:px-8 py-8 space-y-6">

            <!-- Header -->
            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-white tracking-wide">Automation & Webhooks</h1>
                    <p class="mt-1 text-sm text-neutral-400">
                        Configure HTTP webhook endpoints to receive real-time event notifications from ZioraCMS.
                    </p>
                </div>
                <div class="mt-4 sm:mt-0 flex gap-2">
                    <UButton
                        color="primary"
                        variant="soft"
                        icon="ph:sparkle"
                        @click.prevent="openAiModal"
                    >
                        AI Architect
                    </UButton>
                    <UButton
                        color="primary"
                        icon="ph:plus-circle"
                        @click.prevent="openCreateModal"
                    >
                        Create Webhook
                    </UButton>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="p-5 rounded-2xl bg-neutral-900/80 border border-neutral-800 space-y-2">
                    <div class="flex items-center justify-between text-xs text-neutral-400">
                        <span>Total Webhooks</span>
                        <UIcon name="ph:plugs-connected" class="text-indigo-400 w-5 h-5" />
                    </div>
                    <div class="text-2xl font-bold text-white">{{ webhooks?.total ?? webhooks?.data?.length ?? 0 }}</div>
                </div>
                <div class="p-5 rounded-2xl bg-neutral-900/80 border border-neutral-800 space-y-2">
                    <div class="flex items-center justify-between text-xs text-neutral-400">
                        <span>Active Webhooks</span>
                        <UIcon name="ph:check-circle" class="text-emerald-400 w-5 h-5" />
                    </div>
                    <div class="text-2xl font-bold text-white">{{ activeWebhooksCount }}</div>
                </div>
                <div class="p-5 rounded-2xl bg-neutral-900/80 border border-neutral-800 space-y-2">
                    <div class="flex items-center justify-between text-xs text-neutral-400">
                        <span>Event Security</span>
                        <UIcon name="ph:shield-check" class="text-amber-400 w-5 h-5" />
                    </div>
                    <div class="text-sm font-semibold text-amber-400">HMAC-SHA256 Signed</div>
                </div>
            </div>

            <!-- Table -->
            <BaseTableWrapper>
                <UTable
                    :columns="columns"
                    :data="webhookList"
                    class="flex-1"
                >
                    <template #name-cell="{ row }">
                        <div class="font-medium text-white">{{ row.original.name }}</div>
                    </template>
                    <template #url-cell="{ row }">
                        <span class="text-xs font-mono text-neutral-400 truncate max-w-xs block">{{ row.original.url }}</span>
                    </template>
                    <template #events-cell="{ row }">
                        <div class="flex flex-wrap gap-1">
                            <UBadge
                                v-for="event in getEventsArray(row.original.events)"
                                :key="event"
                                size="xs"
                                variant="subtle"
                                color="primary"
                            >
                                {{ event }}
                            </UBadge>
                        </div>
                    </template>
                    <template #status-cell="{ row }">
                        <UBadge
                            size="xs"
                            variant="subtle"
                            :color="row.original.is_active ? 'success' : 'neutral'"
                        >
                            {{ row.original.is_active ? 'Active' : 'Inactive' }}
                        </UBadge>
                    </template>
                    <template #action-cell="{ row }">
                        <UDropdownMenu
                            arrow
                            :content="{ align: 'end' }"
                            :items="getDropdownItems(row.original)"
                        >
                            <UButton
                                icon="i-lucide:ellipsis-vertical"
                                color="neutral"
                                variant="ghost"
                            />
                        </UDropdownMenu>
                    </template>
                </UTable>

                <template #footer>
                    <BasePagination :data="webhooks" />
                </template>
            </BaseTableWrapper>

        </div>

        <!-- Create / Edit Webhook Modal -->
        <UModal v-model:open="showCreateModal">
            <template #content>
                <UCard class="border border-neutral-800 bg-neutral-900">
                    <template #header>
                        <div class="flex items-center justify-between">
                            <h3 class="text-base font-semibold text-white flex items-center gap-2">
                                <UIcon name="ph:plugs-connected" class="text-primary-400" />
                                {{ editingId ? 'Edit Webhook' : 'Create Webhook' }}
                            </h3>
                            <UButton
                                color="neutral"
                                variant="ghost"
                                icon="ph:x"
                                class="-my-1"
                                @click.prevent="closeModal"
                            />
                        </div>
                    </template>

                    <UForm :state="form" @submit="submit" class="space-y-4">
                        <UFormField label="Name" required :error="form.errors.name">
                            <UInput v-model="form.name" placeholder="e.g. Production Webhook Listener" class="w-full" />
                        </UFormField>

                        <UFormField label="Payload URL" required :error="form.errors.url">
                            <UInput v-model="form.url" placeholder="https://api.myapp.com/webhooks/ziora" class="w-full" />
                        </UFormField>

                        <UFormField label="Subscribed Events" required :error="form.errors.events">
                            <div class="space-y-2 max-h-48 overflow-y-auto border border-neutral-800 rounded-xl p-3 bg-neutral-950/60">
                                <div
                                    v-for="evt in availableEvents"
                                    :key="evt.value"
                                    class="flex items-center gap-3"
                                >
                                    <UCheckbox
                                        :id="`evt_${evt.value}`"
                                        :model-value="form.events.includes(evt.value)"
                                        @update:model-value="() => toggleEvent(evt.value)"
                                    />
                                    <label :for="`evt_${evt.value}`" class="text-sm text-neutral-300 cursor-pointer">
                                        {{ evt.label }}
                                    </label>
                                </div>
                            </div>
                        </UFormField>

                        <UFormField label="Signing Secret (Optional)" :error="form.errors.secret">
                            <UInput v-model="form.secret" placeholder="whsec_..." class="w-full font-mono text-xs" />
                        </UFormField>

                        <div class="flex items-center gap-3 pt-2">
                            <UCheckbox id="is_active_wh" v-model="form.is_active" />
                            <label for="is_active_wh" class="text-sm text-neutral-300 cursor-pointer">
                                Active webhook endpoint
                            </label>
                        </div>

                        <div class="flex justify-end gap-3 pt-4">
                            <UButton color="neutral" variant="ghost" @click="closeModal">Cancel</UButton>
                            <UButton type="submit" color="primary" :loading="form.processing">
                                {{ editingId ? 'Update Webhook' : 'Create Webhook' }}
                            </UButton>
                        </div>
                    </UForm>
                </UCard>
            </template>
        </UModal>

        <!-- AI Rule Generator Modal -->
        <AiPromptModal
            v-model:isOpen="showAiModal"
            title="AI Webhook & Automation Architect"
            description="Generate automation rules, email sequences, and webhook event listeners using AI."
            endpoint="/api/automation/generate-rule"
            placeholder="Generate a webhook notification rule when an order is created or entry is updated..."
            :suggestions="['Send webhook on new entry created', 'Trigger alert on page published']"
            @success="handleAiSuccess"
        />
    </AdminLayout>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import { useForm, router, Head } from '@inertiajs/vue3';
import AdminLayout from '@modules/Dashboard/resources/layouts/AdminLayout.vue';
import BaseTableWrapper from '@/components/BaseTableWrapper.vue';
import BasePagination from '@/components/BasePagination.vue';
import AiPromptModal from '@/components/AiPromptModal.vue';
import { useToast } from '@nuxt/ui/runtime/composables/useToast.js';
import type { BreadcrumbItem, DropdownMenuItem } from '@nuxt/ui';

const breadcrumbs: BreadcrumbItem[] = [
    { label: 'Automation & Webhooks', icon: 'ph:plugs-connected' },
];

const props = defineProps<{
    webhooks?: any;
}>();

const toast = useToast();
const showCreateModal = ref(false);
const showAiModal = ref(false);
const editingId = ref<number | null>(null);
const webhookList = computed(() => {
    if (!props.webhooks) return [];
    if (Array.isArray(props.webhooks.data)) return props.webhooks.data;
    if (Array.isArray(props.webhooks)) return props.webhooks;
    return [];
});

function getEventsArray(eventsData: any): string[] {
    if (Array.isArray(eventsData)) return eventsData;
    if (typeof eventsData === 'string') {
        try {
            const parsed = JSON.parse(eventsData);
            return Array.isArray(parsed) ? parsed : [];
        } catch {
            return [];
        }
    }
    return [];
}

function toggleEvent(evtValue: string) {
    const idx = form.events.indexOf(evtValue);
    if (idx > -1) {
        form.events.splice(idx, 1);
    } else {
        form.events.push(evtValue);
    }
}

const form = useForm({
    name: '',
    url: '',
    events: [] as string[],
    secret: '',
    is_active: true,
});

const activeWebhooksCount = computed(() => {
    return webhookList.value.filter((w: any) => w.is_active).length;
});

const availableEvents = [
    { label: 'Entry Created (entry.created)', value: 'entry.created' },
    { label: 'Entry Updated (entry.updated)', value: 'entry.updated' },
    { label: 'Entry Deleted (entry.deleted)', value: 'entry.deleted' },
    { label: 'Page Published (page.published)', value: 'page.published' },
];

function openCreateModal() {
    editingId.value = null;
    form.reset();
    showCreateModal.value = true;
}

function openAiModal() {
    showAiModal.value = true;
}

function closeModal() {
    showCreateModal.value = false;
    form.reset();
    editingId.value = null;
}

function editWebhook(webhook: any) {
    editingId.value = webhook.id;
    form.name = webhook.name;
    form.url = webhook.url;
    form.events = getEventsArray(webhook.events);
    form.secret = webhook.secret || '';
    form.is_active = Boolean(webhook.is_active);
    showCreateModal.value = true;
}

function handleAiSuccess(result: any) {
    const auto = result.automation || result;
    if (auto) {
        toast.add({
            title: 'AI Webhook Generated',
            description: `Successfully generated automation rule "${auto.rule_name || 'Webhook'}"!`,
            color: 'success',
        });
        router.reload();
    }
}

function submit() {
    if (editingId.value) {
        form.put(route('admin.webhooks.update', editingId.value), {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route('admin.webhooks.store'), {
            onSuccess: () => closeModal(),
        });
    }
}

function getDropdownItems(row: any): DropdownMenuItem[] {
    return [
        {
            label: 'Edit',
            icon: 'ph:note-pencil',
            onSelect: () => editWebhook(row),
        },
        {
            type: 'separator',
        },
        {
            label: 'Delete',
            icon: 'ph:trash',
            color: 'error',
            onSelect: () => {
                if (confirm(`Delete webhook "${row.name}"?`)) {
                    router.delete(route('admin.webhooks.destroy', row.id));
                }
            },
        },
    ];
}

const columns = [
    { accessorKey: 'name', header: 'Name' },
    { accessorKey: 'url', header: 'URL' },
    { accessorKey: 'events', header: 'Events' },
    { accessorKey: 'status', header: 'Status' },
    { id: 'action' },
];
</script>

<style scoped></style>