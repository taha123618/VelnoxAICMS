<template>
    <BaseModalPage
        v-model="showModal"
        title="Create Category"
    >
        <form
            @submit.prevent="submit"
            class="space-y-3"
        >
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
    </BaseModalPage>
</template>

<script setup lang="ts">
import BaseModalPage from '@/components/BaseModalPage.vue';

const showModal = ref<boolean>(true)

const form = useForm({
    name: ''
});

function submit() {
    form.post(route('admin.categories.store'), {
        onSuccess: () => {
            showModal.value = false
        },
        onError: () => console.log('Error occured'),
    });
}
</script>

<style scoped></style>