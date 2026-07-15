<template>
    <AdminLayout :breadcrumbs="breadcrumbs">

        <Head title="Pages" />
        <BaseTableWrapper>
            <template #filters>
                <UInput
                    :loading="isLoading"
                    icon="i-lucide-search"
                    size="md"
                    v-model="form.search"
                    variant="outline"
                    placeholder="Search..."
                />
            </template>
            <template #actions>
                <UButton
                    v-if="authUser.can.create_roles"
                    color="primary"
                    variant="solid"
                    as-child
                >
                    <ModalLink :href="route('admin.roles.create')">
                        <UIcon name="ph:plus-circle" />
                        New Role
                    </ModalLink>
                </UButton>
            </template>
            <UTable
                ref="tableRef"
                sticky
                :columns="columns"
                :data="data.data"
                class="flex-1"
                :loading="isLoading"
                :sorting="sortingOptions"
                @update:sorting="handleSort"
            >
                <template #action-cell="{ row }">
                    <UDropdownMenu
                        arrow
                        :content="{
                            align: 'end'
                        }"
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
                <BasePagination
                    :data="data"
                    v-model="form.page"
                />
            </template>
        </BaseTableWrapper>

        <BaseConfirmationModal
            v-model="isRevealed"
            @close="cancel"
            @cancel="cancel"
            @confirm="confirm"
        />
    </AdminLayout>
</template>

<script setup lang="ts">
import BasePagination from '@/components/BasePagination.vue';
import BaseTableWrapper from '@/components/BaseTableWrapper.vue';
import { useDatatable } from '@/composables/use-datatable';
import AdminLayout from '@modules/Dashboard/resources/layouts/AdminLayout.vue';
import { ITableData } from '@/types/types';
import type { BreadcrumbItem, DropdownMenuItem } from '@nuxt/ui';
import { visitModal } from '@inertiaui/modal-vue'
import { useAuth } from '@modules/Auth/resources/composables/use-auth';

const UButton = resolveComponent('UButton')

const breadcrumbs: BreadcrumbItem[] = [
    {
        label: 'Roles',
        icon: 'ph:shield-checkered'
    },
];

const authUser = useAuth()

const { data, filters } = defineProps<{
    data: ITableData;
    filters: Record<string, any>;
}>();

const { getSortableHeader, form, handleSort, sortingOptions, isLoading } = useDatatable(filters, data)

const { isRevealed, cancel, confirm, reveal } = useConfirmDialog();

function getDropdownItems(rowData: Record<string, any>): DropdownMenuItem[] {
    return [
        {
            label: 'Edit label',
            icon: 'ph:note-pencil',
            class: !rowData.can.update && 'hidden',
            disabled: !rowData.can.update,
            onSelect: () => visitModal(route('admin.roles.edit', [rowData.id]))
        },
        {
            label: 'Manage permissions',
            icon: 'ph:note-pencil',
            class: !rowData.can.update && 'hidden',
            disabled: !rowData.can.update,
            onSelect: () => visitModal(route('admin.roles.permissions', [rowData.id]))
        },
        {
            type: 'separator' as const,
            class: !rowData.can.delete && 'hidden',
        },
        {
            label: 'Delete role',
            icon: 'ph:trash',
            color: 'error',
            class: !rowData.can.delete && 'hidden',
            disabled: !rowData.can.delete,
            onSelect: async () => {
                const { isCanceled } = await reveal();
                if (isCanceled) {
                    return;
                }
                router.delete(route('admin.roles.destroy', [rowData.id]), {
                    onSuccess: () => console.log('done'),
                });
            }
        }
    ]
}

const columns = [
    {
        accessorKey: 'label',
        header: ({ column }: { column: any }) => getSortableHeader(column, UButton, 'Label')
    },
    {
        accessorKey: 'name',
        header: ({ column }: { column: any }) => getSortableHeader(column, UButton, 'Name')
    },
    {
        accessorKey: 'total_permissions',
        header: 'Permissions'
    },
    {
        accessorKey: 'total_users',
        header: 'Users'
    },
    {
        accessorKey: 'created_at',
        header: ({ column }: { column: any }) => getSortableHeader(column, UButton, 'Created at')
    },
    {
        id: 'action',
    },
];

</script>

<style scoped></style>