<template>
    <AdminLayout :breadcrumbs="breadcrumbs">
        <Head title="Collections" />
        <BaseTableWrapper>
            <template #actions>
                <UButton
                    color="primary"
                    variant="solid"
                    as-child
                >
                    <ModalLink :href="route('admin.collections.create')">
                        <UIcon name="ph:plus-circle" />
                        New Collection
                    </ModalLink>
                </UButton>
            </template>
            
            <div class="p-6">
                <div v-if="!collections || collections.length === 0" class="text-center py-8 text-neutral-500">
                    No collections found. Create your first content type!
                </div>
                
                <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <UCard v-for="collection in collections" :key="collection.id" class="flex flex-col">
                        <template #header>
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-lg font-semibold text-white">{{ collection.name }}</h3>
                                    <p class="text-sm text-neutral-400">/{{ collection.slug }}</p>
                                </div>
                            </div>
                        </template>
                        
                        <div class="text-sm text-neutral-300 mb-4 h-10 overflow-hidden">
                            {{ collection.description || 'No description provided.' }}
                        </div>
                        
                        <template #footer>
                            <div class="flex items-center gap-2 mt-auto pt-4 border-t border-neutral-800">
                                <UButton 
                                    color="primary" 
                                    variant="soft" 
                                    size="sm"
                                    as-child
                                >
                                    <Link :href="route('admin.collections.entries.index', collection.id)">
                                        Entries
                                    </Link>
                                </UButton>
                                <UButton 
                                    color="neutral" 
                                    variant="soft" 
                                    size="sm"
                                    as-child
                                >
                                    <Link :href="route('admin.collections.edit', collection.id)">
                                        Settings
                                    </Link>
                                </UButton>
                            </div>
                        </template>
                    </UCard>
                </div>
            </div>
        </BaseTableWrapper>
    </AdminLayout>
</template>

<script setup lang="ts">
import AdminLayout from '@modules/Dashboard/resources/layouts/AdminLayout.vue';
import BaseTableWrapper from '@/components/BaseTableWrapper.vue';
import { Link } from '@inertiajs/vue3';
import type { BreadcrumbItem } from '@nuxt/ui';

const breadcrumbs: BreadcrumbItem[] = [
    { label: 'Content', icon: 'ph:database' },
    { label: 'Collections' },
];

defineProps<{
    collections: any[];
}>();
</script>
