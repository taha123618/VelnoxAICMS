<template>
    <AdminLayout :breadcrumbs="breadcrumbs">
        <Head :title="collection.name" />
        <BaseTableWrapper>
            <template #filters>
                <UInput
                    v-model="search"
                    icon="i-lucide-search"
                    size="md"
                    variant="outline"
                    placeholder="Search entries..."
                />
            </template>
            <template #actions>
                <div class="flex gap-2">
                    <UButton
                        color="primary"
                        variant="soft"
                        icon="ph:sparkle-duotone"
                        @click.prevent="() => { showAiModal = true }"
                    >
                        Generate Article with AI
                    </UButton>
                    <UButton
                        color="primary"
                        variant="solid"
                        as-child
                    >
                        <Link :href="route('admin.collections.entries.create', collection.id)" class="flex gap-1.5 items-center">
                            <UIcon name="ph:plus-circle" class="w-5 h-5 shrink-0" />
                            New {{ collection.name.replace(/s$/, '') }}
                        </Link>
                    </UButton>
                </div>
            </template>
            <UTable
                :columns="columns"
                :data="filteredEntries"
                class="flex-1"
            >
                <template #title-cell="{ row }">
                    {{ row.original.title }}
                </template>
                <template #status-cell="{ row }">
                    <UBadge
                        size="md"
                        variant="subtle"
                        :color="row.original.status === 'published' ? 'success' : 'neutral'"
                        class="capitalize"
                    >
                        {{ row.original.status }}
                    </UBadge>
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
        </BaseTableWrapper>

        <AiPromptModal
            v-model:isOpen="showAiModal"
            title="AI Article & Content Generator"
            description="Generate high-quality structured articles and blog posts using AI."
            endpoint="/api/content/generate-article"
            placeholder="Write a blog post about modern web architecture trends..."
            :suggestions="['Modern Web Architecture Trends', 'Top 10 CMS Features for 2026', 'AI-Driven Content Management Guide']"
            @success="handleAiSuccess"
        />
    </AdminLayout>
</template>

<script setup lang="ts">
import AdminLayout from '@modules/Dashboard/resources/layouts/AdminLayout.vue';
import BaseTableWrapper from '@/components/BaseTableWrapper.vue';
import { ref, computed } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import type { BreadcrumbItem, DropdownMenuItem } from '@nuxt/ui';
import AiPromptModal from '@/components/AiPromptModal.vue';

const props = defineProps<{
    collection: any;
    entries: any[];
}>();

const showAiModal = ref(false);
const search = ref('');

function handleAiSuccess(result: any) {
    if (result.article || result) {
        router.reload();
    }
}

const filteredEntries = computed(() => {
    if (!search.value) return props.entries;
    const lowerSearch = search.value.toLowerCase();
    return props.entries.filter(e => e.title.toLowerCase().includes(lowerSearch));
});

const breadcrumbs: BreadcrumbItem[] = [
    { label: 'Content', icon: 'ph:database', to: route('admin.collections.index') },
    { label: props.collection.name },
];

function getDropdownItems(row: any): DropdownMenuItem[] {
    return [
        {
            label: 'Edit',
            icon: 'ph:note-pencil',
            onSelect: () => router.visit(route('admin.collections.entries.edit', [props.collection.id, row.id]))
        },
        {
            type: 'separator' as const,
        },
        {
            label: 'Delete',
            icon: 'ph:trash',
            color: 'error',
            onSelect: () => {
                if (confirm('Are you sure you want to delete this entry?')) {
                    router.delete(route('admin.collections.entries.destroy', [props.collection.id, row.id]));
                }
            }
        }
    ];
}

const columns = [
    { accessorKey: 'title', header: 'Title' },
    { accessorKey: 'slug', header: 'Slug' },
    { accessorKey: 'status', header: 'Status' },
    { accessorKey: 'created_at', header: 'Created at', cell: ({row}: any) => new Date(row.original.created_at).toLocaleDateString() },
    { id: 'action' },
];
</script>
