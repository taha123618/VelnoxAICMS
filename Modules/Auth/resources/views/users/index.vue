<template>
    <AdminLayout :breadcrumbs="breadcrumbs">

        <Head title="Manage Users" />
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
                    v-if="authUser.can.create_users"
                    color="primary"
                    variant="solid"
                    as-child
                >
                    <ModalLink :href="route('admin.users.create')">
                        <UIcon name="ph:plus-circle" />
                        New User
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
                <template #avatar-cell="{ row }">
                    <UAvatar :alt="(row.original.name as string)" />
                </template>

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
import { ModalLink, visitModal } from '@inertiaui/modal-vue';
import { useAuth } from '@modules/Auth/resources/composables/use-auth';
import { BreadcrumbItem, DropdownMenuItem } from '@nuxt/ui';

const UButton = resolveComponent('UButton')

const authUser = useAuth()


const breadcrumbs: BreadcrumbItem[] = [
    {
        label: 'Users',
        icon: 'ph:users'
    },
];

const { data, filters } = defineProps<{
    data: ITableData;
    filters: Record<string, any>
}>();

const { getSortableHeader, form, handleSort, sortingOptions, isLoading } = useDatatable(filters, data)

const { isRevealed, cancel, confirm, reveal } = useConfirmDialog();

const columns = [
    {
        accessorKey: 'avatar',
        header: ''
    },
    {
        accessorKey: 'first_name',
        header: ({ column }: { column: any }) => getSortableHeader(column, UButton, 'First name')
    },
    {
        accessorKey: 'last_name',
        header: ({ column }: { column: any }) => getSortableHeader(column, UButton, 'Last name')
    },
    {
        accessorKey: 'email',
        header: ({ column }: { column: any }) => getSortableHeader(column, UButton, 'Email')
    },
    {
        accessorKey: 'role_label',
        header: 'Role'
    },
    {
        accessorKey: 'created_at',
        header: ({ column }: { column: any }) => getSortableHeader(column, UButton, 'Created at')
    },
    {
        id: 'action',
    },
];

function getDropdownItems(rowData: Record<string, any>): DropdownMenuItem[] {
    return [
        {
            label: 'Edit user',
            icon: 'ph:note-pencil',
            class: !rowData.can.update && 'hidden',
            disabled: !rowData.can.update,
            onSelect: () => visitModal(route('admin.users.edit', [rowData.id]))
        },
        {
            label: 'Send password reset',
            icon: 'ph:password',
            class: !rowData.can.update && 'hidden',
            disabled: !rowData.can.update,
            onSelect: () => {
                router.post(route('admin.users.password.email', [rowData.id]), {}, {
                    onBefore: () => isLoading.value = true,
                    onFinish: () => isLoading.value = false
                })
            }
        },
        {
            type: 'separator' as const,
            class: !rowData.can.delete && 'hidden',
        },
        {
            label: 'Delete user',
            icon: 'ph:trash',
            color: 'error',
            class: !rowData.can.delete && 'hidden',
            disabled: !rowData.can.delete,
            onSelect: async () => {
                const { isCanceled } = await reveal();
                if (isCanceled) {
                    return;
                }
                router.delete(route('admin.users.destroy', [rowData.id]), {
                    onSuccess: () => console.log('done'),
                });
            }
        }
    ]
}

</script>

<style scoped></style>