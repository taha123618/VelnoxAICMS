<template>
    <AdminLayout :breadcrumbs="breadcrumbs">
        <Head title="Roles & Permissions" />

        <div class="px-4 sm:px-6 lg:px-8 py-8 space-y-6">

            <!-- Header -->
            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-white tracking-wide">Roles & Permissions</h1>
                    <p class="mt-1 text-sm text-neutral-400">
                        Manage user roles and configure permission assignments for fine-grained access control.
                    </p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <UButton color="primary" icon="ph:plus-circle" @click="openCreateModal">
                        Create Role
                    </UButton>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <div class="p-5 rounded-2xl bg-neutral-900/80 border border-neutral-800 space-y-2">
                    <div class="flex items-center justify-between text-xs text-neutral-400">
                        <span>Total Roles</span>
                        <UIcon name="ph:shield" class="text-indigo-400 w-5 h-5" />
                    </div>
                    <div class="text-2xl font-bold text-white">{{ (roles as any[])?.length ?? 0 }}</div>
                </div>
                <div class="p-5 rounded-2xl bg-neutral-900/80 border border-neutral-800 space-y-2">
                    <div class="flex items-center justify-between text-xs text-neutral-400">
                        <span>Total Permissions</span>
                        <UIcon name="ph:key" class="text-amber-400 w-5 h-5" />
                    </div>
                    <div class="text-2xl font-bold text-white">{{ (permissions as any[])?.length ?? 0 }}</div>
                </div>
                <div class="p-5 rounded-2xl bg-neutral-900/80 border border-neutral-800 space-y-2">
                    <div class="flex items-center justify-between text-xs text-neutral-400">
                        <span>Assigned Permissions</span>
                        <UIcon name="ph:check-circle" class="text-emerald-400 w-5 h-5" />
                    </div>
                    <div class="text-2xl font-bold text-white">
                        {{ (roles as any[])?.reduce((acc: number, r: any) => acc + (r.permissions?.length ?? 0), 0) }}
                    </div>
                </div>
            </div>

            <!-- Roles Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                <UCard
                    v-for="role in (roles as any[])"
                    :key="role.id"
                    class="border border-neutral-800 bg-neutral-900/60 hover:border-primary-700 transition-colors"
                >
                    <template #header>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-primary-500/20 flex items-center justify-center">
                                    <UIcon name="ph:shield-check" class="text-primary-400 w-4 h-4" />
                                </div>
                                <h3 class="text-sm font-semibold text-white capitalize">{{ role.name }}</h3>
                            </div>
                            <div class="flex gap-1">
                                <UButton
                                    size="xs"
                                    color="neutral"
                                    variant="ghost"
                                    icon="ph:pencil"
                                    @click="openEditModal(role)"
                                />
                                <UButton
                                    size="xs"
                                    color="error"
                                    variant="ghost"
                                    icon="ph:trash"
                                    @click="deleteRole(role)"
                                />
                            </div>
                        </div>
                    </template>

                    <div v-if="role.permissions?.length" class="flex flex-wrap gap-1.5">
                        <UBadge
                            v-for="perm in role.permissions"
                            :key="perm.id"
                            size="xs"
                            variant="subtle"
                            color="primary"
                        >
                            {{ perm.name }}
                        </UBadge>
                    </div>
                    <div v-else class="text-xs text-neutral-600 italic">No permissions assigned</div>

                    <template #footer>
                        <div class="text-xs text-neutral-500 flex items-center gap-1">
                            <UIcon name="ph:key" class="text-neutral-600" />
                            {{ role.permissions?.length ?? 0 }} permissions
                        </div>
                    </template>
                </UCard>
            </div>

        </div>

        <!-- Create / Edit Modal -->
        <UModal v-model:open="isModalOpen">
            <template #content>
                <UCard>
                    <template #header>
                        <h3 class="text-base font-semibold text-white flex items-center gap-2">
                            <UIcon name="ph:shield" class="text-primary-400" />
                            {{ editingRole ? 'Edit Role' : 'Create Role' }}
                        </h3>
                    </template>

                    <UForm :state="form" @submit="submitForm" class="space-y-5">
                        <UFormField label="Role Name" :error="form.errors.name" required>
                            <UInput
                                v-model="form.name"
                                placeholder="e.g. Editor, Moderator"
                                class="w-full"
                            />
                        </UFormField>

                        <UFormField label="Assign Permissions">
                            <div class="space-y-2 max-h-60 overflow-y-auto border border-neutral-800 rounded-xl p-3 bg-neutral-950/60">
                                <div
                                    v-for="permission in (permissions as any[])"
                                    :key="permission.id"
                                    class="flex items-center gap-3"
                                >
                                    <UCheckbox
                                        :id="`perm_${permission.id}`"
                                        :model-value="form.permissions.includes(permission.name)"
                                        @update:model-value="(v: boolean | 'indeterminate') => {
                                            const checked = v === true;
                                            if (checked) form.permissions.push(permission.name);
                                            else form.permissions = form.permissions.filter((p: string) => p !== permission.name);
                                        }"
                                    />
                                    <label
                                        :for="`perm_${permission.id}`"
                                        class="text-sm text-neutral-300 cursor-pointer"
                                    >
                                        {{ permission.name }}
                                    </label>
                                </div>
                            </div>
                        </UFormField>
                    </UForm>

                    <template #footer>
                        <div class="flex justify-end gap-3">
                            <UButton color="neutral" variant="ghost" @click="closeModal">Cancel</UButton>
                            <UButton
                                color="primary"
                                :loading="form.processing"
                                @click="submitForm"
                            >
                                {{ editingRole ? 'Update Role' : 'Create Role' }}
                            </UButton>
                        </div>
                    </template>
                </UCard>
            </template>
        </UModal>

    </AdminLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import { useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@modules/Dashboard/resources/layouts/AdminLayout.vue';
import type { BreadcrumbItem } from '@nuxt/ui';

defineProps<{
    roles: any[];
    permissions: any[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { label: 'ACL — Roles & Permissions', icon: 'ph:shield-check' },
];

const isModalOpen = ref(false);
const editingRole = ref<any>(null);

const form = useForm({
    name: '',
    permissions: [] as string[],
});

function openCreateModal() {
    editingRole.value = null;
    form.reset();
    form.permissions = [];
    isModalOpen.value = true;
}

function openEditModal(roleToEdit: any) {
    editingRole.value = roleToEdit;
    form.name = roleToEdit.name;
    form.permissions = roleToEdit.permissions?.map((p: any) => p.name) ?? [];
    isModalOpen.value = true;
}

function closeModal() {
    isModalOpen.value = false;
    editingRole.value = null;
    form.reset();
}

function submitForm() {
    if (editingRole.value) {
        form.put(route('admin.acl.roles.update', editingRole.value.id), {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route('admin.acl.roles.store'), {
            onSuccess: () => closeModal(),
        });
    }
}

function deleteRole(roleToDelete: any) {
    if (confirm(`Delete role "${roleToDelete.name}"? This action cannot be undone.`)) {
        router.delete(route('admin.acl.roles.destroy', roleToDelete.id));
    }
}
</script>

<style scoped></style>