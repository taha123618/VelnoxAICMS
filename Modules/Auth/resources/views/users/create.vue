<template>
    <BaseModalPage
        ref="modalRef"
        v-model="showModal"
        title="Add New User"
        :is-slide-over="true"
        size="max-w-sm"
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
                :error="form.errors.password"
                label="Password"
            >
                <UInput
                    v-model="form.password"
                    type="password"
                    class="w-full"
                />
            </UFormField>

            <UFormField
                required
                :error="form.errors.password_confirmation"
                label="Confirm Password"
            >
                <UInput
                    v-model="form.password_confirmation"
                    type="password"
                    class="w-full"
                />
            </UFormField>
            <BaseSelect
                required
                :error="form.errors.role"
                label="Role"
                value-key="name"
                label-key="label"
                v-model="form.role"
                :create-url="route('admin.roles.create')"
                :options="roles"
                :on-create="() => modalRef.reload()"
            />


            <div class="flex justify-end items-center py-2">
                <UButton
                    type="submit"
                    :loading="form.processing"
                >
                    Add User
                </UButton>
            </div>
        </form>
    </BaseModalPage>
</template>

<script setup lang="ts">
import BaseModalPage from '@/components/BaseModalPage.vue';
import BaseSelect from '@/components/BaseSelect.vue';

const { roles } = defineProps<{
    roles: Modules.Auth.Data.RoleData[]
}>()

const showModal = ref(true);
const modalRef = ref()

const form = useForm({
    first_name: '',
    last_name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: ''
});

function submit() {
    form.post(route('admin.users.store'), {
        onSuccess: () => {
            form.reset();
            showModal.value = false
        }
    });
}
</script>

<style scoped></style>
