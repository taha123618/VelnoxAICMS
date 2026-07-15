<template>
    <BaseModalPage v-model="showModal" title="Create Layout">
        <form
            class="space-y-3"
            @submit.prevent="submit"
        >
            <UFormField
                required
                :error="form.errors.name"
                label="Name"
            >
                <UInput
                    v-model="form.name"
                    placeholder="Layout name"
                    class="w-full"
                />
            </UFormField>
            <div class="flex justify-end items-center">
                <UButton
                    type="submit"
                    :loading="form.processing"
                >
                    Create
                </UButton>
            </div>
        </form>
    </BaseModalPage>
</template>

<script setup lang="ts">
import BodyElement from '@modules/Builder/resources/draggables/static/body/config';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import BaseModalPage from '@/components/BaseModalPage.vue';

const showModal = ref(true);

const form = useForm({
    name: '',
    content: [new ZioraElement(BodyElement)] as any,
});


function submit() {
    form.post(route('admin.layouts.store'), {
        onSuccess: () => {
            form.reset();
            showModal.value = false
        }
    });
}
</script>

<style scoped></style>
