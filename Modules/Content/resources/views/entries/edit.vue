<template>
    <AdminLayout :breadcrumbs="breadcrumbs">
        <Head :title="`Edit ${entry.title}`" />
        
        <div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-white">Edit {{ entry.title }}</h1>
                </div>
                
                <div class="flex gap-2">
                    <UButton
                        color="error"
                        variant="soft"
                        @click="deleteEntry"
                        icon="ph:trash"
                    >
                        Delete
                    </UButton>
                    <UButton
                        color="primary"
                        @click="submit"
                        :loading="form.processing"
                        icon="ph:check"
                    >
                        Save Changes
                    </UButton>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Main Form Area -->
                <div class="md:col-span-2 space-y-6">
                    <UCard>
                        <div class="space-y-6">
                            <!-- Standard Fields -->
                            <UFormField required label="Title" :error="form.errors.title">
                                <UInput v-model="form.title" class="w-full" size="lg" />
                            </UFormField>

                            <!-- Dynamic Fields from Schema -->
                            <div v-for="field in collection.fields" :key="field.id">
                                <UFormField 
                                    :required="field.is_required" 
                                    :label="field.name" 
                                    :error="getFieldError(field.handle)"
                                >
                                    <!-- Dynamic Inputs based on type -->
                                    <UInput 
                                        v-if="field.type === 'text' || field.type === 'number'" 
                                        :type="field.type === 'number' ? 'number' : 'text'"
                                        v-model="form.entry_data[field.handle]" 
                                        class="w-full" 
                                    />
                                    
                                    <UTextarea 
                                        v-else-if="field.type === 'textarea'" 
                                        v-model="form.entry_data[field.handle]" 
                                        class="w-full" 
                                        :rows="4" 
                                    />
                                    
                                    <UCheckbox 
                                        v-else-if="field.type === 'boolean'" 
                                        v-model="form.entry_data[field.handle]" 
                                        :label="field.name" 
                                    />
                                    
                                    <!-- Fallback -->
                                    <UInput 
                                        v-else 
                                        v-model="form.entry_data[field.handle]" 
                                        class="w-full" 
                                        :placeholder="`Type: ${field.type}`"
                                    />
                                </UFormField>
                            </div>
                        </div>
                    </UCard>
                </div>
                
                <!-- Sidebar -->
                <div class="space-y-6">
                    <UCard>
                        <template #header>
                            <h3 class="text-base font-semibold leading-6 text-white">Publishing</h3>
                        </template>
                        
                        <div class="space-y-4">
                            <UFormField required label="Slug" :error="form.errors.slug">
                                <UInput v-model="form.slug" class="w-full" />
                            </UFormField>

                            <UFormField label="Status" :error="form.errors.status" v-if="collection.is_publishable">
                                <BaseSelect 
                                    v-model="form.status" 
                                    :options="[
                                        { label: 'Draft', value: 'draft' },
                                        { label: 'Published', value: 'published' }
                                    ]" 
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
import { useForm, router } from '@inertiajs/vue3';
import type { BreadcrumbItem } from '@nuxt/ui';
import BaseSelect from '@/components/BaseSelect.vue';

const props = defineProps<{
    collection: any;
    entry: any;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { label: 'Content', icon: 'ph:database', to: route('admin.collections.index') },
    { label: props.collection.name, to: route('admin.collections.entries.index', props.collection.id) },
    { label: props.entry.title },
];

// Initialize dynamic data object merging existing data
const initialData: Record<string, any> = { ...(props.entry.data || {}) };
if (props.collection.fields) {
    props.collection.fields.forEach((f: any) => {
        if (initialData[f.handle] === undefined) {
            initialData[f.handle] = f.type === 'boolean' ? false : '';
        }
    });
}

const form = useForm({
    title: props.entry.title,
    slug: props.entry.slug,
    status: props.entry.status,
    entry_data: initialData,
});

function getFieldError(handle: string): string | undefined {
    return (form.errors as Record<string, string | undefined>)[`data.${handle}`];
}

function submit() {
    form.transform((data) => ({
        title: data.title,
        slug: data.slug,
        status: data.status,
        data: data.entry_data,
    })).put(route('admin.collections.entries.update', [props.collection.id, props.entry.id]));
}

function deleteEntry() {
    if (confirm('Are you sure you want to delete this entry?')) {
        router.delete(route('admin.collections.entries.destroy', [props.collection.id, props.entry.id]));
    }
}
</script>
