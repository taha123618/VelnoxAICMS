<template>
    <AdminLayout :breadcrumbs="breadcrumbs">

        <Head title="Posts" />
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
                    v-if="authUser.can.create_posts"
                    color="primary"
                    variant="solid"
                    as-child
                >
                    <ModalLink :href="route('admin.posts.create')">
                        <UIcon name="ph:plus-circle" />
                        New Post
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
                <template #isDifferentFromPublishedVersion-cell="{ row }">
                    <UBadge
                        size="sm"
                        variant="subtle"
                        :color="row.getValue('isDifferentFromPublishedVersion') ? 'warning' : 'success'"
                        :label="row.getValue('isDifferentFromPublishedVersion') ? 'Yes' : 'No'"
                    />
                </template>

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
import { router } from '@inertiajs/vue3';
import { useAuth } from '@modules/Auth/resources/composables/use-auth';
import type { BreadcrumbItem, DropdownMenuItem } from '@nuxt/ui';

const authUser = useAuth()
const UBadge = resolveComponent('UBadge');
const UButton = resolveComponent('UButton')


const breadcrumbs: BreadcrumbItem[] = [
    {
        label: 'Posts',
        icon: 'ph:push-pin'
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
            label: rowData.isPublished ? 'View post' : 'Preview post',
            icon: 'ph:eye',
            href: rowData.isPublished ? route('pages.show', [rowData.slug]) : route('pages.preview', [rowData.id]),
            target: '_blank'
        },
        {
            label: 'Edit post',
            icon: 'ph:note-pencil',
            onSelect: () => router.visit(route('admin.posts.edit', [rowData.id])),
            disabled: !rowData.can.update,
            class: !rowData.can.update && 'hidden'
        },
        {
            label: 'Duplicate post',
            icon: 'ph:copy-simple',
            class: !rowData.can.update && 'hidden',
            disabled: !rowData.can.update,
            onSelect: () => {
                router.post(route('admin.pages.duplicate', [rowData.id]));
            }
        },
        {
            label: rowData.isPublished ? 'Unpublish post' : 'Publish post',
            icon: rowData.isPublished ? 'ph:rocket' : 'ph:rocket-launch',
            disabled: rowData.isFrontpage || !rowData.can.update,
            class: !rowData.can.update && 'hidden',
            color: rowData.isPublished ? 'error' : 'neutral',
            onSelect: () => {
                router.put(route('admin.pages.status', [rowData.id]), {}, {
                    onSuccess: () => console.log('done'),
                });
            }
        },
        {
            type: 'separator' as const,
            class: !rowData.can.delete && 'hidden'
        },
        {
            label: 'Delete',
            icon: 'ph:trash',
            color: 'error',
            class: !rowData.can.delete && 'hidden',
            disabled: rowData.isFrontpage || !rowData.can.delete,
            onSelect: async () => {
                const { isCanceled } = await reveal();
                if (isCanceled) {
                    return;
                }
                router.delete(route('admin.posts.destroy', [rowData.id]), {
                    onSuccess: () => console.log('done'),
                });
            }
        }
    ]
}

const columns = [
    {
        accessorKey: 'title',
        header: ({ column }: { column: any }) => getSortableHeader(column, UButton, 'Title')
    },
    {
        accessorKey: 'url',
        header: 'Path',
    },
    {
        accessorKey: 'isDifferentFromPublishedVersion',
        header: 'Unpublished changes',
    },
    {
        accessorKey: 'categoryName',
        header: 'Category',
    },
    {
        accessorKey: 'layoutName',
        header: 'Layout',
    },
    {
        accessorKey: 'status',
        header: 'Status',
        cell: ({ row }) => {
            return h(
                UBadge,
                {
                    class: 'capitalize',
                    variant: 'subtle',
                    color: row.original.statusColor,
                },
                () => row.getValue('status'),
            );
        },
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
