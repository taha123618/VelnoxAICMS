<template>
    <BaseModalPage
        v-model="showModal"
        title="Create Category"
    >
        <form
            @submit.prevent="submit"
            class="space-y-4"
        >
            <div class="flex justify-end">
                <UButton
                    color="primary"
                    variant="ghost"
                    size="xs"
                    icon="ph:sparkle"
                    @click.prevent="() => { showAiModal = true; }"
                >
                    AI Generator
                </UButton>
            </div>

            <UFormField
                required
                label="Name"
                :error="form.errors.name"
            >
                <UInput
                    class="w-full"
                    placeholder="Category name"
                    v-model="form.name"
                />
            </UFormField>

            <div class="flex items-center justify-end">
                <UButton
                    :loading="form.processing"
                    :disabled="!form.isDirty"
                    type="submit"
                    size="sm"
                    label="Create"
                />
            </div>
        </form>

        <AiPromptModal
            v-model:isOpen="showAiModal"
            title="AI Category Taxonomy Generator"
            description="Generate category names and subcategory structures using AI."
            endpoint="/api/category/generate-taxonomy"
            placeholder="Generate categories for an online electronics store..."
            :suggestions="['E-commerce Tech Categories', 'Digital Marketing Topics']"
            @success="handleAiSuccess"
        />
    </BaseModalPage>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import BaseModalPage from '@/components/BaseModalPage.vue';
import AiPromptModal from '@/components/AiPromptModal.vue';

const showModal = ref<boolean>(true);
const showAiModal = ref<boolean>(false);

const form = useForm({
    name: ''
});

function handleAiSuccess(result: any) {
    if (result.taxonomy && result.taxonomy.name) {
        form.name = result.taxonomy.name;
    }
}

function submit() {
    form.post(route('admin.categories.store'), {
        onSuccess: () => {
            showModal.value = false;
        },
        onError: () => console.log('Error occurred'),
    });
}
</script>

<style scoped></style>