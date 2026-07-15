<template>
    <AdminLayout :breadcrumbs="breadcrumbs">

        <Head title="Layouts" />
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
                    v-if="authUser.can.create_layouts"
                    color="primary"
                    variant="solid"
                    as-child
                >
                    <ModalLink :href="route('admin.layouts.create')">
                        <UIcon name="ph:plus-circle" />
                        New Layout
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
import { ModalLink } from '@inertiaui/modal-vue';
import { useAuth } from '@modules/Auth/resources/composables/use-auth';
import { BreadcrumbItem, DropdownMenuItem } from '@nuxt/ui';

const UButton = resolveComponent('UButton')

const authUser = useAuth()

const breadcrumbs: BreadcrumbItem[] = [
    {
        label: 'Layouts',
        icon: 'ph:layout'
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
        accessorKey: 'name',
        header: ({ column }: { column: any }) => getSortableHeader(column, UButton, 'Name')
    },
    {
        accessorKey: 'totalPages',
        header: 'Total pages'
    },
    {
        accessorKey: 'totalPosts',
        header: 'Total posts'
    },
    {
        accessorKey: 'created',
        header: ({ column }: { column: any }) => getSortableHeader(column, UButton, 'Created at')
    },
    {
        accessorKey: 'updated',
        header: ({ column }: { column: any }) => getSortableHeader(column, UButton, 'Updated at')
    },
    {
        id: 'action',
    },
];

function getDropdownItems(rowData: Record<string, any>): DropdownMenuItem[] {
    return [
        {
            label: 'Edit layout',
            icon: 'ph:note-pencil',
            class: !rowData.can.update && 'hidden',
            disabled: !rowData.can.update,
            onSelect: () => router.visit(route('admin.layouts.edit', [rowData.id]))
        },
        {
            type: 'separator' as const,
            class: !rowData.can.delete && 'hidden',
            disabled: !rowData.can.delete,
        },
        {
            label: 'Delete layout',
            icon: 'ph:trash',
            color: 'error',
            class: !rowData.can.delete && 'hidden',
            disabled: !rowData.can.delete,
            onSelect: async () => {
                const { isCanceled } = await reveal();
                if (isCanceled) {
                    return;
                }
                router.delete(route('admin.layouts.destroy', [rowData.id]), {
                    onSuccess: () => console.log('done'),
                });
            }
        }
    ]
}

</script>

<style scoped></style>
