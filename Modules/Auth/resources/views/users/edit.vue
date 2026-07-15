<template>
    <BaseModalPage
        v-model="showModal"
        title="Edit User"
        :is-slide-over="true"
    >
        <form
            class="space-y-3"
            @submit.prevent="submit"
        >
            <UFormField
                required
                :error="form.errors.first_name"
                label="First Name"
            >
                <UInput
                    v-model="form.first_name"
                    class="w-full"
                />
            </UFormField>
            <UFormField
                required
                :error="form.errors.last_name"
                label="Last Name"
            >
                <UInput
                    v-model="form.last_name"
                    class="w-full"
                />
            </UFormField>

            <UFormField
                required
                :error="form.errors.email"
                label="Email"
            >
                <UInput
                    v-model="form.email"
                    type="email"
                    class="w-full"
                />
            </UFormField>

            <UFormField
                required
                :error="form.errors.role"
                label="Role"
            >
                <USelect
                    :items="roles"
                    v-model="form.role"
                    label-key="label"
                    value-key="name"
                    class="w-full"
                />
            </UFormField>

            <div class="flex justify-end items-center">
                <UButton
                    type="submit"
                    :loading="form.processing"
                >
                    Update user
                </UButton>
            </div>
        </form>
    </BaseModalPage>
</template>

<script setup lang="ts">
import BaseModalPage from '@/components/BaseModalPage.vue';

const { user, roles } = defineProps<{
    user: Modules.Auth.Data.UserData;
    roles: Modules.Auth.Data.RoleData[]
}>()

const showModal = ref(true);

const form = useForm({
    first_name: user.first_name,
    last_name: user.last_name,
    email: user.email,
    role: user.role_name || undefined
});


function submit() {
    form.put(route('admin.users.update', [user.id]), {
        onSuccess: () => {
            form.reset();
            showModal.value = false
        }
    });
}
</script>

<style scoped></style>
