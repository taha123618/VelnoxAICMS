<template>
    <BaseModalPage
        title="Create Menu"
        v-model="showModal"
    >
        <form
            class="space-y-2"
            @submit.prevent="submit"
        >
            <UFormField
                required
                :error="form.errors.name"
                label="Name"
            >
                <UInput
                    v-model="form.name"
                    class="w-full"
                />
            </UFormField>

            <UButton
                type="submit"
                :loading="form.processing"
                label="Submit"
            />
        </form>
    </BaseModalPage>
</template>

<script setup lang="ts">
import BaseModalPage from '@/components/BaseModalPage.vue';

const form = useForm({
    name: ''
});

const showModal = ref<boolean>(true)


function submit() {
    form.post(route('admin.menus.store'), {
        onSuccess: () => {
            form.reset();
            showModal.value = false
        }
    });
}
</script>

<style scoped></style>
