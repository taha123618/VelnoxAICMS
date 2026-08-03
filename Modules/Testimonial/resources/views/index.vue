<template>

    <Head title="Testimonials" />

    <AdminLayout :breadcrumbs="breadcrumbs">

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
                <div class="flex gap-2">
                    <UButton
                        color="primary"
                        variant="soft"
                        icon="ph:sparkle"
                        @click.prevent="showAiModal = true"
                    >
                        Generate with AI
                    </UButton>
                    <UButton
                        v-if="authUser.can.create_testimonials"
                        color="primary"
                        variant="solid"
                        as-child
                    >
                        <ModalLink :href="route('admin.testimonials.create')">
                            <UIcon name="ph:plus-circle" />
                            New Testimonial
                        </ModalLink>
                    </UButton>
                </div>
            </template>
            <UTable
                ref="tableRef"
                sticky
                :columns="columns"
                :data="testimonialList"
                class="flex-1"
                :loading="isLoading"
                :sorting="sortingOptions"
                @update:sorting="handleSort"
            >
                <template #comment-cell="{ row }">
                    <p class="max-w-sm truncate">
                        {{ row.original.comment }}
                    </p>
                </template>
                <template #name-cell="{ row }">
                    <div class="flex items-center gap-2">
                        <UAvatar :src="(row.original.avatar as string)" />
                        <div class="space-y-0">
                            <p>{{ row.original.name }}</p>
                            <p class="text-xs text-neutral-500">
                                {{ row.original.title }}
                            </p>
                        </div>
                    </div>
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

        <AiPromptModal
            v-model:isOpen="showAiModal"
            title="AI Testimonial Generator"
            description="Generate realistic, high-conversion customer testimonials using AI."
            endpoint="/api/testimonial/generate-testimonial"
            placeholder="Generate a 5-star customer review for our SaaS CMS platform..."
            :suggestions="['5-Star SaaS Review', 'E-commerce Customer Feedback', 'Agency Client Case Study']"
            @success="handleAiSuccess"
        />
    </AdminLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import AiPromptModal from '@/components/AiPromptModal.vue';
import { useToast } from '@nuxt/ui/runtime/composables/useToast.js';
import BasePagination from '@/components/BasePagination.vue';
import BaseTableWrapper from '@/components/BaseTableWrapper.vue';
import { useDatatable } from '@/composables/use-datatable';
import AdminLayout from '@modules/Dashboard/resources/layouts/AdminLayout.vue';
import { ITableData } from '@/types/types';
import { router } from '@inertiajs/vue3';
import { ModalLink, visitModal } from '@inertiaui/modal-vue';
import { useAuth } from '@modules/Auth/resources/composables/use-auth';
import type { BreadcrumbItem, DropdownMenuItem } from '@nuxt/ui';

const UBadge = resolveComponent('UBadge');
const UButton = resolveComponent('UButton');
const authUser = useAuth();

const breadcrumbs: BreadcrumbItem[] = [
    {
        label: 'Testimonials',
        icon: 'ph:quotes'
    },
];

const { data, filters } = defineProps<{
    data: ITableData;
    filters: Record<string, any>;
}>();

const { getSortableHeader, form, handleSort, sortingOptions, isLoading } = useDatatable(filters, data);
const { isRevealed, cancel, confirm, reveal } = useConfirmDialog();

const showAiModal = ref(false);
const testimonialList = ref<any[]>([...(data?.data ?? [])]);

function handleAiSuccess(result: any) {
    const testimonial = result.testimonial || result;
    if (testimonial) {
        useToast().add({
            title: 'Testimonial Generated!',
            description: `Generated testimonial from "${testimonial.name || 'Customer'}"`,
            color: 'success',
        });
        router.reload();
    }
}


function getDropdownItems(rowData: Record<string, any>): DropdownMenuItem[] {
    return [
        {
            label: 'Edit testimonial',
            icon: 'ph:note-pencil',
            class: !rowData.can.update && 'hidden',
            disabled: !rowData.can.update,
            onSelect: () => {
                visitModal(route('admin.testimonials.edit', [rowData.id]))
            }
        },
        {
            label: rowData.isPublished ? 'Unpublish' : 'Publish',
            icon: rowData.isPublished ? 'ph:rocket' : 'ph:rocket-launch',
            color: rowData.isPublished ? 'error' : 'neutral',
            class: !rowData.can.update && 'hidden',
            disabled: !rowData.can.update,
            onSelect: () => {
                router.put(route('admin.testimonials.status', [rowData.id]), {}, {
                    onSuccess: () => console.log('done'),
                });
            }
        },
        {
            type: 'separator' as const,
            class: !rowData.can.delete && 'hidden',
        },
        {
            label: 'Delete testimonial',
            icon: 'ph:trash',
            color: 'error',
            disabled: !rowData.can.delete,
            class: !rowData.can.delete && 'hidden',
            onSelect: async () => {
                const { isCanceled } = await reveal();
                if (isCanceled) {
                    return;
                }
                router.delete(route('admin.testimonials.destroy', [rowData.id]), {
                    onSuccess: () => console.log('done'),
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
        accessorKey: 'comment',
        header: 'Comment',
    },
    {
        accessorKey: 'status',
        header: 'Status',
        cell: ({ row }: { row: any }) => {
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
        id: 'action',
    },
];

</script>
