<template>
    <AdminLayout :breadcrumbs="breadcrumbs">
        <Head title="Automation & Webhooks" />
        <BaseTableWrapper>
            <template #actions>
                <UButton
                    color="primary"
                    variant="solid"
                    @click.prevent="() => { showCreateModal = true; }"
                    class="flex gap-1.5 items-center"
                >
                    <UIcon name="ph:plus-circle" class="w-5 h-5 shrink-0" />
                    Create Webhook
                </UButton>
            </template>
            <UTable
                :columns="columns"
                :data="webhooks.data"
                class="flex-1"
            >
                <template #name-cell="{ row }">
                    <div class="font-medium text-gray-900 dark:text-white">{{ row.original.name }}</div>
                </template>
                <template #url-cell="{ row }">
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ row.original.url }}</span>
                </template>
                <template #events-cell="{ row }">
                    <div class="flex flex-wrap gap-1">
                        <UBadge
                            v-for="event in row.original.events"
                            :key="event"
                            size="sm"
                            variant="subtle"
                            color="primary"
                        >
                            {{ event }}
                        </UBadge>
                    </div>
                </template>
                <template #status-cell="{ row }">
                    <UBadge
                        size="md"
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
        </BaseTableWrapper>

        <!-- Create/Edit Modal -->
        <UModal v-model="showCreateModal">
            <UCard class="divide-y divide-gray-100 dark:divide-gray-800">
                <template #header>
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-white">
                            {{ editingId ? 'Edit Webhook' : 'Create Webhook' }}
                        </h3>
                        <UButton color="neutral" variant="ghost" icon="i-heroicons-x-mark-20-solid" class="-my-1" @click="closeModal" />
                    </div>
                </template>

                <UForm :state="form" @submit="submit" class="space-y-4">
                    <UFormField label="Name" name="name" required>
                        <UInput v-model="form.name" placeholder="Webhook Name" />
                    </UFormField>
                    
                    <UFormField label="URL" name="url" required>
                        <UInput v-model="form.url" type="url" placeholder="https://example.com/webhook" />
                    </UFormField>

                    <UFormField label="Events" name="events" required>
                        <USelectMenu
                            v-model="form.events"
                            :options="eventOptions"
                            value-attribute="value"
                            multiple
                            placeholder="Select events"
                        />
                    </UFormField>

                    <UFormField label="Secret (Optional)" name="secret">
                        <UInput v-model="form.secret" type="password" placeholder="Webhook Secret" />
                    </UFormField>

                    <UCheckbox v-model="form.is_active" label="Active" />

                    <div class="flex justify-end gap-3 mt-6">
                        <UButton color="neutral" variant="soft" @click="closeModal">Cancel</UButton>
                        <UButton type="submit" color="primary" :loading="form.processing">Save</UButton>
                    </div>
                </UForm>
            </UCard>
        </UModal>
    </AdminLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useForm, router, Head } from '@inertiajs/vue3';
import AdminLayout from '@modules/Dashboard/resources/layouts/AdminLayout.vue';
import BaseTableWrapper from '@/components/BaseTableWrapper.vue';
import type { DropdownMenuItem } from '@nuxt/ui';

const breadcrumbs = [
    { label: 'Settings', icon: 'ph:gear' },
    { label: 'Automation & Webhooks' },
];

const props = defineProps({
    webhooks: Object as any,
});

const showCreateModal = ref(false);
const editingId = ref(null);

const form = useForm({
    name: '',
    url: '',
    events: [],
    secret: '',
    is_active: true,
});

const eventOptions = [
    { label: 'Entry Created', value: 'entry.created' },
    { label: 'Entry Updated', value: 'entry.updated' },
    { label: 'Entry Deleted', value: 'entry.deleted' },
    { label: 'Page Published', value: 'page.published' }
];

const closeModal = () => {
    showCreateModal.value = false;
    form.reset();
    editingId.value = null;
};

const editWebhook = (webhook: any) => {
    editingId.value = webhook.id;
    form.name = webhook.name;
    form.url = webhook.url;
    form.events = webhook.events;
    form.secret = webhook.secret;
    form.is_active = webhook.is_active;
    showCreateModal.value = true;
};

const submit = () => {
    if (editingId.value) {
        form.put(route('admin.webhooks.update', editingId.value), {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route('admin.webhooks.store'), {
            onSuccess: () => closeModal(),
        });
    }
};

function getDropdownItems(row: any): DropdownMenuItem[] {
    return [
        {
            label: 'Edit',
            icon: 'ph:note-pencil',
            onSelect: () => editWebhook(row)
        },
        {
            type: 'separator'
        },
        {
            label: 'Delete',
            icon: 'ph:trash',
            color: 'error',
            onSelect: () => {
                if (confirm('Are you sure you want to delete this webhook?')) {
                    router.delete(route('admin.webhooks.destroy', row.id));
                }
            }
        }
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