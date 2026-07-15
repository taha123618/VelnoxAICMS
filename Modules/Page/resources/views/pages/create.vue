<template>
    <BaseModalPage
        title="Create Page"
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
                label="Page title"
            >
                <UInput
                    v-model="form.title"
                    class="w-full"
                />
            </UFormField>

            <BaseSelect
                required
                :error="form.errors.layout"
                label="Page layout"
                v-model="form.layout"
                :create-url="route('admin.layouts.create')"
                :options="layouts"
                :on-create="() => modalRef.reload()"
            />

            <UButton
                type="submit"
                :loading="form.processing"
            >
                Submit
            </UButton>
        </form>
    </BaseModalPage>
</template>

<script setup lang="ts">
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import wrapperElement from '@modules/Builder/resources/draggables/static/wrapper/config';
import BaseModalPage from '@/components/BaseModalPage.vue';
import BaseSelect from '@/components/BaseSelect.vue';

const { layouts } = defineProps<{
    layouts: Record<string, any>[];
}>();

const modalRef = ref()

const showModal = ref(true);

const form = useForm({
    title: '',
    layout: '',
    content: [new ZioraElement(wrapperElement)] as any,
});

function submit() {
    form.post(route('admin.pages.store'), {
        onSuccess: () => {
            form.reset();
            showModal.value = false
        }
    });
}
</script>

<style scoped></style>
