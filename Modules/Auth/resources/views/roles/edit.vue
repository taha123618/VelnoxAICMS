<template>
    <BaseModalPage
        title="Update Role"
        v-model="showModal"
    >
        <form
            class="space-y-2"
            @submit.prevent="submit"
        >
            <UFormField
                required
                :error="form.errors.label"
                label="Label"
            >
                <UInput
                    v-model="form.label"
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

const { role } = defineProps<{
    role: Modules.Auth.Data.RoleData
}>()

const form = useForm({
    label: role.label
});


function submit() {
    form.put(route('admin.roles.update', [role.id]), {
        onSuccess: () => {
            showModal.value = false
        }
    });
}
</script>

<style scoped></style>
