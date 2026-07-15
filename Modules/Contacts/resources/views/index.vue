<template>
    <AdminLayout :breadcrumbs="breadcrumbs">

        <Head title="Contact messages" />
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
import {visitModal} from '@inertiaui/modal-vue'

const UButton = resolveComponent('UButton')

const breadcrumbs: BreadcrumbItem[] = [
    {
        label: 'Contact Messages',
        icon: 'ph:mailbox'
    },
];


const { data, filters } = defineProps<{
    data: ITableData;
    filters: Record<string, any>
}>();

const { isRevealed, cancel, confirm, reveal } = useConfirmDialog();

const { getSortableHeader, form, handleSort, sortingOptions, isLoading } = useDatatable(filters, data)


function getDropdownItems(rowData: Record<string, any>): DropdownMenuItem[] {
    return [
        {
            label: 'View',
            icon: 'ph:eye',
            onSelect: () => {
                visitModal(route('admin.contacts.show', [rowData.id]))
            },
            target: '_self'
        },
        {
            type: 'separator' as const,
            class: rowData.isFrontpage ? 'hidden' : '',
        },
        {
            label: 'Delete',
            icon: 'ph:trash',
            color: 'error',
            onSelect: async () => {
                const { isCanceled } = await reveal();
                if (isCanceled) {
                    return;
                }
                router.delete(route('admin.contacts.destroy', [rowData.id]), {
                    onSuccess: () => console.log('deleted'),
                });
            }
        }
    ]
}

const columns = [
    {
        accessorKey: 'name',
        header: ({ column }: { column: any }) => getSortableHeader(column, UButton, 'Name')
    },
    {
        accessorKey: 'email',
        header: ({ column }: { column: any }) => getSortableHeader(column, UButton, 'Email')
    },
    {
        accessorKey: 'subject',
        header: 'Subject',
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