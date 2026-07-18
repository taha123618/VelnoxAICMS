<template>
    <AdminLayout :breadcrumbs="breadcrumbs">
        <Head title="Schema Fields" />
        
        <div class="max-w-5xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-white">{{ collection.name }} Schema</h1>
                    <p class="text-sm text-neutral-400">Define the data structure for entries in this collection.</p>
                </div>
                
                <UButton
                    color="primary"
                    @click="showAddFieldModal = true"
                    icon="ph:plus"
                >
                    Add Field
                </UButton>
            </div>
            
            <UCard>
                <div v-if="!fields || fields.length === 0" class="text-center py-8 text-neutral-500">
                    No fields defined yet. Add your first field to get started.
                </div>
                
                <div v-else class="divide-y divide-neutral-800">
                    <div v-for="field in fields" :key="field.id" class="py-4 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <UIcon name="ph:dots-six-vertical" class="text-neutral-500 cursor-move" />
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-medium text-white">{{ field.name }}</span>
                                    <UBadge size="sm" variant="subtle" color="primary">{{ field.type }}</UBadge>
                                    <UBadge size="sm" variant="subtle" color="error" v-if="field.is_required">Required</UBadge>
                                </div>
                                <div class="text-sm text-neutral-400 font-mono mt-1">{{ field.handle }}</div>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <UButton 
                                size="sm" 
                                color="neutral" 
                                variant="ghost" 
                                icon="ph:pencil-simple"
                                @click="editField(field)"
                            />
                            <UButton 
                                size="sm" 
                                color="error" 
                                variant="ghost" 
                                icon="ph:trash"
                                @click="deleteField(field)"
                            />
                        </div>
                    </div>
                </div>
            </UCard>
        </div>
        
        <!-- Field Modal -->
        <UModal v-model="showAddFieldModal" :title="isEditing ? 'Edit Field' : 'Add Field'">
            <UCard>
                <template #header>
                    <h3 class="text-base font-semibold leading-6 text-white">{{ isEditing ? 'Edit Field' : 'Add Field' }}</h3>
                </template>
                
                <form @submit.prevent="submitField" class="space-y-4">
                    <UFormField required label="Field Name" :error="form.errors.name">
                        <UInput v-model="form.name" @update:model-value="generateHandle" class="w-full" />
                    </UFormField>
                    
                    <UFormField required label="Handle" :error="form.errors.handle" help="Used in API and code. Must be unique.">
                        <UInput v-model="form.handle" @input="handleModified = true" class="w-full" :disabled="isEditing" />
                    </UFormField>
                    
                    <UFormField required label="Field Type" :error="form.errors.type">
                        <BaseSelect 
                            v-model="form.type" 
                            :options="fieldTypes" 
                        />
                    </UFormField>
                    
                    <UFormField label="Validation Rules" :error="form.errors.rules" help="Laravel validation rules (e.g., 'max:255|unique:entries,data->slug')">
                        <UInput v-model="form.rules" class="w-full" />
                    </UFormField>
                    
                    <UFormField>
                        <UCheckbox v-model="form.is_required" label="Required Field" />
                    </UFormField>
                    
                    <div class="flex justify-end gap-2 pt-4">
                        <UButton color="neutral" variant="ghost" @click="showAddFieldModal = false">Cancel</UButton>
                        <UButton type="submit" color="primary" :loading="form.processing">Save Field</UButton>
                    </div>
                </form>
            </UCard>
        </UModal>
    </AdminLayout>
</template>

<script setup lang="ts">
import AdminLayout from '@modules/Dashboard/resources/layouts/AdminLayout.vue';
import { useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import type { BreadcrumbItem } from '@nuxt/ui';
import BaseSelect from '@/components/BaseSelect.vue';

const props = defineProps<{
    collection: any;
    fields: any[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { label: 'Content', icon: 'ph:database', to: route('admin.collections.index') },
    { label: props.collection.name, to: route('admin.collections.edit', props.collection.id) },
    { label: 'Schema Fields' },
];

const showAddFieldModal = ref(false);
const isEditing = ref(false);
const editingId = ref(null);

const form = useForm({
    name: '',
    handle: '',
    type: 'text',
    is_required: false,
    rules: '',
    settings: {},
});

const fieldTypes = [
    { label: 'Single Line Text', value: 'text' },
    { label: 'Multi Line Text', value: 'textarea' },
    { label: 'Rich Text', value: 'richtext' },
    { label: 'Number', value: 'number' },
    { label: 'Boolean / Toggle', value: 'boolean' },
    { label: 'Date / Time', value: 'datetime' },
    { label: 'Media / File', value: 'media' },
    { label: 'Relation', value: 'relation' },
];

function slugify(text: string) {
    return text.toString().toLowerCase()
        .replace(/\s+/g, '_')           
        .replace(/[^\w\_]+/g, '')       
        .replace(/\_\_+/g, '_')         
        .replace(/^_+/, '')             
        .replace(/_+$/, '');            
}

const handleModified = ref(false);

function generateHandle(value: string) {
    if (!isEditing.value && !handleModified.value) {
        form.handle = slugify(value);
    }
}

function editField(field: any) {
    isEditing.value = true;
    editingId.value = field.id;
    form.name = field.name;
    form.handle = field.handle;
    form.type = field.type;
    form.is_required = field.is_required;
    form.rules = field.rules || '';
    form.settings = field.settings || {};
    showAddFieldModal.value = true;
}

function deleteField(field: any) {
    if (confirm(`Are you sure you want to delete the field "${field.name}"? Data in entries will not be deleted but won't be visible.`)) {
        router.delete(route('admin.collections.fields.destroy', [props.collection.id, field.id]));
    }
}

function submitField() {
    if (isEditing.value) {
        form.put(route('admin.collections.fields.update', [props.collection.id, editingId.value]), {
            onSuccess: () => {
                showAddFieldModal.value = false;
                resetForm();
            }
        });
    } else {
        form.post(route('admin.collections.fields.store', props.collection.id), {
            onSuccess: () => {
                showAddFieldModal.value = false;
                resetForm();
            }
        });
    }
}

function resetForm() {
    form.reset();
    isEditing.value = false;
    editingId.value = null;
    handleModified.value = false;
}
</script>
