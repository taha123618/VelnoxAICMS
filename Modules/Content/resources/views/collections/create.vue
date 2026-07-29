<template>
    <BaseModalPage
        title="Create Collection"
        v-model="showModal"
        ref="modalRef"
        size="max-w-md"
    >
        <form
            class="space-y-4"
            @submit.prevent="submit"
        >
            <UFormField
                required
                :error="form.errors.name"
                label="Collection Name"
                help="e.g., Blog Posts, Products, Authors"
            >
                <UInput
                    v-model="form.name"
                    @update:model-value="generateSlug"
                    class="w-full"
                />
            </UFormField>

            <UFormField
                required
                label="Slug"
                :error="form.errors.slug"
                help="The URL-friendly identifier for the collection."
            >
                <UInput
                    v-model="form.slug"
                    @input="slugModified = true"
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
                    :rows="3"
                />
            </UFormField>

            <UFormField
                :error="form.errors.is_publishable"
                label="Features"
            >
                <UCheckbox
                    v-model="form.is_publishable"
                    label="Enable Draft/Published status for entries"
                />
            </UFormField>

            <div class="flex justify-end gap-2 pt-2">
                <UButton
                    color="neutral"
                    variant="soft"
                    @click.prevent="() => { showModal = false; }"
                >
                    Cancel
                </UButton>
                <UButton
                    type="submit"
                    :loading="form.processing"
                    color="primary"
                >
                    Create
                </UButton>
            </div>
        </form>
    </BaseModalPage>
</template>

<script setup lang="ts">
import BaseModalPage from '@/components/BaseModalPage.vue';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const modalRef = ref();
const showModal = ref(true);

const form = useForm({
    name: '',
    slug: '',
    description: '',
    is_publishable: true,
});

function slugify(text: string) {
    return text.toString().toLowerCase()
        .replace(/\s+/g, '-')           // Replace spaces with -
        .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
        .replace(/\-\-+/g, '-')         // Replace multiple - with single -
        .replace(/^-+/, '')             // Trim - from start of text
        .replace(/-+$/, '');            // Trim - from end of text
}

const slugModified = ref(false);

function generateSlug(value: string) {
    if (!slugModified.value) {
        form.slug = slugify(value);
    }
}

function submit() {
    form.post(route('admin.collections.store'), {
        onSuccess: () => {
            form.reset();
            showModal.value = false;
        }
    });
}
</script>
