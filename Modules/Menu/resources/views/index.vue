<template>
    <AdminLayout :breadcrumbs="breadcrumbs">

        <Head title="Menus" />
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
                    v-if="authUser.can.create_menus"
                    color="primary"
                    variant="solid"
                    as-child
                >
                    <ModalLink :href="route('admin.menus.create')">
                        <UIcon name="ph:plus-circle" />
                        New Menu
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
import type { BreadcrumbItem, DropdownMenuItem } from '@nuxt/ui'
import AdminLayout from '@modules/Dashboard/resources/layouts/AdminLayout.vue';
import { ITableData } from '@/types/types';
import { useDatatable } from '@/composables/use-datatable';
import BaseTableWrapper from '@/components/BaseTableWrapper.vue';
import BasePagination from '@/components/BasePagination.vue';
import { ModalLink, visitModal } from '@inertiaui/modal-vue'
import { useAuth } from '@modules/Auth/resources/composables/use-auth';

const breadcrumbs: BreadcrumbItem[] = [
    {
        label: 'Menus',
        icon: 'ph:list'
    }
];

const { data, filters } = defineProps<{
    data: ITableData;
    filters: Record<string, any>
}>();

const authUser = useAuth()

const { getSortableHeader, form, handleSort, sortingOptions, isLoading } = useDatatable(filters, data)
const UButton = resolveComponent('UButton')

const { isRevealed, cancel, confirm, reveal } = useConfirmDialog();


const columns = [
    {
        accessorKey: 'name',
        header: ({ column }: { column: any }) => getSortableHeader(column, UButton, 'Name')
    },
    {
        accessorKey: 'totalItems',
        header: 'Items',
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
            label: 'Edit menu',
            icon: 'ph:note-pencil',
            onSelect: () => visitModal(route('admin.menus.edit', [rowData.id]))
        },
        {
            type: 'separator' as const
        },
        {
            label: 'Delete menu',
            icon: 'ph:trash',
            color: 'error',
            disabled: rowData.isFrontpage,
            onSelect: async () => {
                const { isCanceled } = await reveal();
                if (isCanceled) {
                    return;
                }
                router.delete(route('admin.menus.destroy', [rowData.id]), {
                    onSuccess: () => console.log('done'),
                });
            }
        }
    ]
}

</script>
