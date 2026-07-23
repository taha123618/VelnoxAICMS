<template>
    <AdminLayout :breadcrumbs="breadcrumbs">
        <Head title="Localization & Languages" />
        <BaseTableWrapper>
            <template #actions>
                <UButton
                    color="primary"
                    variant="solid"
                    @click.prevent="() => { showCreateModal = true; }"
                    class="flex gap-1.5 items-center"
                >
                    <UIcon name="ph:plus-circle" class="w-5 h-5 shrink-0" />
                    Add Language
                </UButton>
            </template>
            <UTable
                :columns="columns"
                :data="languages.data"
                class="flex-1"
            >
                <template #name-cell="{ row }">
                    <div class="font-medium text-gray-900 dark:text-white">{{ row.original.name }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ row.original.native_name }}</div>
                </template>
                <template #code-cell="{ row }">
                    <span class="uppercase font-mono text-gray-500 dark:text-gray-400">{{ row.original.code }}</span>
                </template>
                <template #status-cell="{ row }">
                    <div class="flex gap-2">
                        <UBadge
                            v-if="row.original.is_default"
                            size="md"
                            variant="subtle"
                            color="primary"
                        >
                            Default
                        </UBadge>
                        <UBadge
                            size="md"
                            variant="subtle"
                            :color="row.original.is_active ? 'success' : 'neutral'"
                        >
                            {{ row.original.is_active ? 'Active' : 'Inactive' }}
                        </UBadge>
                    </div>
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
            <UCard :ui="{ ring: '', divide: 'divide-y divide-gray-100 dark:divide-gray-800' }">
                <template #header>
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-white">
                            {{ editingId ? 'Edit Language' : 'Add Language' }}
                        </h3>
                        <UButton color="neutral" variant="ghost" icon="ph:x-bold" class="-my-1" @click="closeModal" />
                    </div>
                </template>

                <UForm :state="form" @submit="submit" class="space-y-4">
                    <UFormField label="Name (e.g., English)" name="name" required>
                        <UInput v-model="form.name" />
                    </UFormField>
                    
                    <UFormField label="Native Name (e.g., English)" name="native_name">
                        <UInput v-model="form.native_name" />
                    </UFormField>

                    <UFormField label="Code (e.g., en, fr, es-MX)" name="code" required>
                        <UInput v-model="form.code" />
                    </UFormField>

                    <UCheckbox v-model="form.is_active" label="Active" />
                    <UCheckbox v-model="form.is_default" label="Set as Default Language" />

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
    { label: 'Localization & Languages' },
];

const props = defineProps({
    languages: Object as any,
});

const showCreateModal = ref(false);
const editingId = ref(null);

const form = useForm({
    name: '',
    native_name: '',
    code: '',
    is_default: false,
    is_active: true,
});

const closeModal = () => {
    showCreateModal.value = false;
    form.reset();
    editingId.value = null;
};

const editLanguage = (language: any) => {
    editingId.value = language.id;
    form.name = language.name;
    form.native_name = language.native_name;
    form.code = language.code;
    form.is_default = language.is_default;
    form.is_active = language.is_active;
    showCreateModal.value = true;
};

const submit = () => {
    if (editingId.value) {
        form.put(route('admin.languages.update', editingId.value), {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route('admin.languages.store'), {
            onSuccess: () => closeModal(),
        });
    }
};

function getDropdownItems(row: any): DropdownMenuItem[] {
    const items: DropdownMenuItem[] = [
        {
            label: 'Edit',
            icon: 'ph:note-pencil',
            onSelect: () => editLanguage(row)
        }
    ];

    if (!row.is_default) {
        items.push({
            type: 'separator'
        });
        items.push({
            label: 'Delete',
            icon: 'ph:trash',
            color: 'error',
            onSelect: () => {
                if (confirm('Are you sure you want to delete this language?')) {
                    router.delete(route('admin.languages.destroy', row.id));
                }
            }
        });
    }
    return items;
}

const columns = [
    { accessorKey: 'name', header: 'Name' },
    { accessorKey: 'code', header: 'Code' },
    { accessorKey: 'status', header: 'Status' },
    { id: 'action' },
];
</script>