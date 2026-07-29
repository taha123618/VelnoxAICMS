<template>
    <AdminLayout :breadcrumbs="breadcrumbs">
        <Head title="Localization & Languages" />

        <div class="px-4 sm:px-6 lg:px-8 py-8 space-y-6">

            <!-- Header -->
            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-white tracking-wide">Localization & Languages</h1>
                    <p class="mt-1 text-sm text-neutral-400">
                        Manage system locales, active languages, and default translation settings.
                    </p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <UButton
                        color="primary"
                        icon="ph:plus-circle"
                        @click.prevent="openCreateModal"
                    >
                        Add Language
                    </UButton>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="p-5 rounded-2xl bg-neutral-900/80 border border-neutral-800 space-y-2">
                    <div class="flex items-center justify-between text-xs text-neutral-400">
                        <span>Total Languages</span>
                        <UIcon name="ph:translate" class="text-indigo-400 w-5 h-5" />
                    </div>
                    <div class="text-2xl font-bold text-white">{{ languages?.total ?? languages?.data?.length ?? 0 }}</div>
                </div>
                <div class="p-5 rounded-2xl bg-neutral-900/80 border border-neutral-800 space-y-2">
                    <div class="flex items-center justify-between text-xs text-neutral-400">
                        <span>Default Language</span>
                        <UIcon name="ph:star" class="text-amber-400 w-5 h-5" />
                    </div>
                    <div class="text-lg font-semibold text-white">
                        {{ defaultLanguageName }}
                    </div>
                </div>
                <div class="p-5 rounded-2xl bg-neutral-900/80 border border-neutral-800 space-y-2">
                    <div class="flex items-center justify-between text-xs text-neutral-400">
                        <span>Active Locales</span>
                        <UIcon name="ph:check-circle" class="text-emerald-400 w-5 h-5" />
                    </div>
                    <div class="text-2xl font-bold text-white">
                        {{ activeLanguagesCount }}
                    </div>
                </div>
            </div>

            <!-- Table -->
            <BaseTableWrapper>
                <UTable
                    :columns="columns"
                    :data="languages?.data ?? []"
                    class="flex-1"
                >
                    <template #name-cell="{ row }">
                        <div>
                            <div class="font-medium text-white">{{ row.original.name }}</div>
                            <div class="text-xs text-neutral-400">{{ row.original.native_name }}</div>
                        </div>
                    </template>
                    <template #code-cell="{ row }">
                        <UBadge color="neutral" variant="subtle" size="xs" class="font-mono uppercase">
                            {{ row.original.code }}
                        </UBadge>
                    </template>
                    <template #status-cell="{ row }">
                        <div class="flex gap-2">
                            <UBadge
                                v-if="row.original.is_default"
                                size="xs"
                                variant="solid"
                                color="primary"
                            >
                                Default
                            </UBadge>
                            <UBadge
                                size="xs"
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

                <template #footer>
                    <BasePagination :data="languages" />
                </template>
            </BaseTableWrapper>

        </div>

        <!-- Create/Edit Modal -->
        <UModal v-model:open="showCreateModal">
            <template #content>
                <UCard class="border border-neutral-800 bg-neutral-900">
                    <template #header>
                        <div class="flex items-center justify-between">
                            <h3 class="text-base font-semibold text-white flex items-center gap-2">
                                <UIcon name="ph:translate" class="text-primary-400" />
                                {{ editingId ? 'Edit Language' : 'Add Language' }}
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
                        <UFormField label="Name (e.g. English)" name="name" required :error="form.errors.name">
                            <UInput v-model="form.name" placeholder="English" class="w-full" />
                        </UFormField>
                        
                        <UFormField label="Native Name (e.g. English, Español)" name="native_name" :error="form.errors.native_name">
                            <UInput v-model="form.native_name" placeholder="English" class="w-full" />
                        </UFormField>

                        <UFormField label="Code (e.g. en, fr, es-MX)" name="code" required :error="form.errors.code">
                            <UInput v-model="form.code" placeholder="en" class="w-full" />
                        </UFormField>

                        <div class="space-y-3 pt-2">
                            <div class="flex items-center gap-3">
                                <UCheckbox id="is_active" v-model="form.is_active" />
                                <label for="is_active" class="text-sm text-neutral-300 cursor-pointer">
                                    Active language
                                </label>
                            </div>
                            <div class="flex items-center gap-3">
                                <UCheckbox id="is_default" v-model="form.is_default" />
                                <label for="is_default" class="text-sm text-neutral-300 cursor-pointer">
                                    Set as default system language
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 pt-4">
                            <UButton color="neutral" variant="ghost" @click="closeModal">Cancel</UButton>
                            <UButton type="submit" color="primary" :loading="form.processing">
                                {{ editingId ? 'Update Language' : 'Create Language' }}
                            </UButton>
                        </div>
                    </UForm>
                </UCard>
            </template>
        </UModal>

    </AdminLayout>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import { useForm, router, Head } from '@inertiajs/vue3';
import AdminLayout from '@modules/Dashboard/resources/layouts/AdminLayout.vue';
import BaseTableWrapper from '@/components/BaseTableWrapper.vue';
import BasePagination from '@/components/BasePagination.vue';
import type { BreadcrumbItem, DropdownMenuItem } from '@nuxt/ui';

const breadcrumbs: BreadcrumbItem[] = [
    { label: 'Localization & Languages', icon: 'ph:translate' },
];

const props = defineProps<{
    languages?: any;
}>();

const showCreateModal = ref(false);
const editingId = ref<number | null>(null);

const form = useForm({
    name: '',
    native_name: '',
    code: '',
    is_default: false,
    is_active: true,
});

const defaultLanguageName = computed(() => {
    const list = props.languages?.data ?? [];
    const def = list.find((l: any) => l.is_default);
    return def ? def.name : 'English';
});

const activeLanguagesCount = computed(() => {
    const list = props.languages?.data ?? [];
    return list.filter((l: any) => l.is_active).length;
});

function openCreateModal() {
    editingId.value = null;
    form.reset();
    showCreateModal.value = true;
}

function closeModal() {
    showCreateModal.value = false;
    form.reset();
    editingId.value = null;
}

function editLanguage(language: any) {
    editingId.value = language.id;
    form.name = language.name;
    form.native_name = language.native_name || '';
    form.code = language.code;
    form.is_default = Boolean(language.is_default);
    form.is_active = Boolean(language.is_active);
    showCreateModal.value = true;
}

function submit() {
    if (editingId.value) {
        form.put(route('admin.languages.update', editingId.value), {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route('admin.languages.store'), {
            onSuccess: () => closeModal(),
        });
    }
}

function getDropdownItems(row: any): DropdownMenuItem[] {
    const items: DropdownMenuItem[] = [
        {
            label: 'Edit',
            icon: 'ph:note-pencil',
            onSelect: () => editLanguage(row),
        },
    ];

    if (!row.is_default) {
        items.push({
            type: 'separator',
        });
        items.push({
            label: 'Delete',
            icon: 'ph:trash',
            color: 'error',
            onSelect: () => {
                if (confirm(`Delete language "${row.name}"?`)) {
                    router.delete(route('admin.languages.destroy', row.id));
                }
            },
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

<style scoped></style>