<template>
    <BaseModalPage
        title="Create Post"
        v-model="showModal"
        ref="modalRef"
        size="max-w-md"
    >
        <form
            class="space-y-2"
            @submit.prevent="submit"
        >
            <UFormField
                required
                :error="form.errors.title"
                label="Post title"
            >
                <UInput
                    v-model="form.title"
                    class="w-full"
                />
            </UFormField>

            <BaseSelect
                required
                :error="form.errors.layout"
                label="Post layout"
                v-model="form.layout"
                :create-url="route('admin.layouts.create')"
                :options="layouts"
                :on-create="() => modalRef.reload()"
            />

            <BaseSelect
                required
                :error="form.errors.category"
                label="Category"
                v-model="form.category"
                :create-url="route('admin.categories.create')"
                :options="categories"
                :on-create="() => modalRef.reload()"
            />

            <UButton
                @click.prevent="submit"
                type="submit"
                :loading="form.processing"
            >
                Submit
            </UButton>
        </form>
    </BaseModalPage>
</template>

<script setup lang="ts">
import BaseModalPage from '@/components/BaseModalPage.vue';
import BaseSelect from '@/components/BaseSelect.vue';
import wrapperElement from '@modules/Builder/resources/draggables/static/wrapper/config';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';

const { layouts } = defineProps<{
    layouts: Record<string, any>[];
    categories: Record<string, any>[];
}>();

const showModal = ref(true)
const modalRef = ref();

const form = useForm({
    title: '',
    layout: '',
    category: '',
    content: [new VelnoxAIElement(wrapperElement)] as any,
});

function submit() {
    form.post(route('admin.posts.store'), {
        onSuccess: () => {
            form.reset();
            modalRef.value?.close();
            showModal.value = false
        }
    });
}
</script>

<style scoped></style>
