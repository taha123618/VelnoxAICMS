<template>
    <BaseModalPage
        title="Create Role"
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
            >
                Submit
            </UButton>
        </form>
    </BaseModalPage>
</template>

<script setup lang="ts">
import BaseModalPage from '@/components/BaseModalPage.vue';


const showModal = ref(true);

const form = useForm({
    name: ''
});


function submit() {
    form.post(route('admin.roles.store'), {
        onSuccess: () => {
            form.reset();
            showModal.value = false
        }
    });
}
</script>

<style scoped></style>
