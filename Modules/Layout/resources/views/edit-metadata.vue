<template>
    <BaseModalPage
        is-dark
        ref="modalRef"
        v-model="showModal"
        title="Edit layout info">
        <form
            class="space-y-6"
            @submit.prevent="submit">
            <BuilderInput
                label="Name"
                label-position="top"
                placeholder="Layout name"
                :value="form.name"
                :error="form.errors.name"
                @change="form.name = $event" />

            <UButton
                block
                type="submit"
                :disabled="!form.isDirty"
                :loading="form.processing">
                Save changes
            </UButton>
        </form>
    </BaseModalPage>
</template>

<script setup lang="ts">
import BaseModalPage from '@/components/BaseModalPage.vue';
import BuilderInput from '@modules/Builder/resources/components/form/builder-input.vue';

const { layout } = defineProps<{
    layout: Modules.Layout.Data.LayoutData;
}>();

const modalRef = ref<InstanceType<typeof BaseModalPage> | null>(null);
const showModal = ref(true);

const form = useForm({
    name: layout.name,
});

const toast = useToast();

function submit() {
    form.put(route('admin.layouts.meta.update', [layout.id]), {
        preserveScroll: true,
        onSuccess: () =>  modalRef.value?.close(),
        onError: () =>  toast.add({ title: 'Error encountered', color: 'error' }),
    });
}
</script>

<style scoped></style>
