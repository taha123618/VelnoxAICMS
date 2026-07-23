<template>
    <AdminLayout :breadcrumbs="breadcrumbs">
        <Head title="Edit Collection" />
        
        <div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-white">Edit {{ collection.name }}</h1>
                    <p class="text-sm text-neutral-400">Manage collection settings and schema.</p>
                </div>
                
                <div class="flex gap-2">
                    <UButton
                        color="error"
                        variant="soft"
                        @click="deleteCollection"
                        icon="ph:trash"
                    >
                        Delete
                    </UButton>
                    <UButton
                        color="primary"
                        @click="submit"
                        :loading="form.processing"
                    >
                        Save Changes
                    </UButton>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Main Settings -->
                <div class="md:col-span-2 space-y-6">
                    <UCard>
                        <template #header>
                            <h3 class="text-base font-semibold leading-6 text-white">General Settings</h3>
                        </template>
                        
                        <div class="space-y-4">
                            <UFormField
                                required
                                :error="form.errors.name"
                                label="Collection Name"
                            >
                                <UInput
                                    v-model="form.name"
                                    class="w-full"
                                />
                            </UFormField>

                            <UFormField
                                required
                                :error="form.errors.slug"
                                label="Slug"
                            >
                                <UInput
                                    v-model="form.slug"
                                    class="w-full"
                                />
                            </UFormField>

                            <UFormField
                                :error="form.errors.description"
                                label="Description"
                            >
                                <UTextarea
                                    v-model="form.description"
                                    class="w-full"
                                    rows="3"
                                />
                            </UFormField>
                        </div>
                    </UCard>
                    
                    <!-- Fields Link -->
                    <UCard>
                        <template #header>
                            <div class="flex justify-between items-center">
                                <h3 class="text-base font-semibold leading-6 text-white">Schema Fields</h3>
                                <UButton 
                                    color="neutral" 
                                    variant="soft" 
                                    size="sm"
                                    as-child
                                >
                                    <Link :href="route('admin.collections.fields.index', collection.id)" class="flex gap-1.5 items-center">
                                        <UIcon name="ph:pencil-simple" class="w-5 h-5 shrink-0" />
                                        Manage Fields
                                    </Link>
                                </UButton>
                            </div>
                        </template>
                        <p class="text-sm text-neutral-400">
                            Configure the fields that will be available when creating entries for this collection.
                        </p>
                    </UCard>
                </div>
                
                <!-- Sidebar Settings -->
                <div class="space-y-6">
                    <UCard>
                        <template #header>
                            <h3 class="text-base font-semibold leading-6 text-white">Features</h3>
                        </template>
                        
                        <div class="space-y-4">
                            <UFormField
                                :error="form.errors.is_publishable"
                            >
                                <UCheckbox
                                    v-model="form.is_publishable"
                                    label="Enable Draft/Published statuses"
                                    help="If disabled, all entries are considered published immediately."
                                />
                            </UFormField>
                        </div>
                    </UCard>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup lang="ts">
import AdminLayout from '@modules/Dashboard/resources/layouts/AdminLayout.vue';
import { useForm, router, Link } from '@inertiajs/vue3';
import type { BreadcrumbItem } from '@nuxt/ui';

const props = defineProps<{
    collection: any;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { label: 'Content', icon: 'ph:database', to: route('admin.collections.index') },
    { label: props.collection.name },
    { label: 'Settings' },
];

const form = useForm({
    name: props.collection.name,
    slug: props.collection.slug,
    description: props.collection.description,
    is_publishable: props.collection.is_publishable,
});

function submit() {
    form.put(route('admin.collections.update', props.collection.id));
}

function deleteCollection() {
    if (confirm('Are you sure you want to delete this collection? All entries will be lost.')) {
        router.delete(route('admin.collections.destroy', props.collection.id));
    }
}
</script>
