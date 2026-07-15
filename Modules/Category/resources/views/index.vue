<template>
    <AdminLayout :breadcrumbs="breadcrumbs">

        <Head title="Categories" />

        <div class="grid grid-cols-6 gap-4">
            <div class="col-span-2">
                <CreateCategoryForm :categories="data.data" />
            </div>

            <div class="col-span-4">
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
                        <template #url-cell="{ row }">
                            <a
                                :href="row.getValue('url')"
                                target="_blank"
                                class="text-builder-primary"
                            >
                                {{ row.getValue('url') }}
                            </a>
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

            </div>
        </div>

        <BaseConfirmationModal
            v-model="isRevealed"
            @close="cancel"
            @cancel="cancel"
            @confirm="confirm"
        />
    </AdminLayout>
</template>

<script setup lang="ts">
import AdminLayout from '@modules/Dashboard/resources/layouts/AdminLayout.vue';
import type { BreadcrumbItem, DropdownMenuItem } from '@nuxt/ui';
import { visitModal } from '@inertiaui/modal-vue';
import BaseTableWrapper from '@/components/BaseTableWrapper.vue';
import { ITableData } from '@/types/types';
import { useDatatable } from '@/composables/use-datatable';
import CreateCategoryForm from '@modules/Category/resources/components/CreateCategoryForm.vue';
import BasePagination from '@/components/BasePagination.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        label: 'Categories',
        icon: 'ph:folders'
    },
];

const { data, filters } = defineProps<{
    data: ITableData;
    filters: Record<string, any>
}>();

const { getSortableHeader, form, handleSort, sortingOptions, isLoading } = useDatatable(filters, data)

const { isRevealed, cancel, confirm, reveal } = useConfirmDialog();

function getDropdownItems(rowData: Record<string, any>): DropdownMenuItem[] {
    return [
        {
            label: 'Edit Category',
            icon: 'ph:note-pencil',
            class: !rowData.can.update && 'hidden',
            onSelect: () => visitModal(route('admin.categories.edit', [rowData.id]))
        },
        {
            type: 'separator' as const,
            class: !rowData.can.delete && 'hidden'
        },
        {
            label: 'Delete',
            icon: 'ph:trash',
            color: 'error',
            disabled: !rowData.can.delete,
            class: !rowData.can.delete && 'hidden',
            onSelect: async () => {
                const { isCanceled } = await reveal();
                if (isCanceled) {
                    return;
                }
                router.delete(route('admin.categories.destroy', [rowData.id]), {
                    onSuccess: () => console.log('done'),
                });
            }
        }
    ]
}

const UButton = resolveComponent('UButton')

const columns = [
    {
        accessorKey: 'name',
        header: ({ column }: { column: any }) => getSortableHeader(column, UButton, 'Name')
    },
    {
        accessorKey: 'slug',
        header: 'Slug',
    },
    {
        accessorKey: 'description',
        header: 'Description',
    },
    {
        accessorKey: 'parentName',
        header: 'Parent',
    },
    {
        accessorKey: 'totalPosts',
        header: 'Total posts',
    },
    {
        id: 'action',
    },
];

</script>

<style scoped></style>