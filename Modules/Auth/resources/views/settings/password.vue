<template>
    <AdminLayout :breadcrumbs="breadcrumbs">
        <Head title="Change Password" />
        <SettingsWrapper>
            <form
                class="space-y-6"
                @submit.prevent="submit"
            >
                <UFormField
                    label="Current password"
                    required
                    :error="form.errors.current_password"
                >
                    <UInput
                        required
                        autofocus
                        type="password"
                        v-model="form.current_password"
                        class="w-full"
                        placeholder="Current Password"
                    />
                </UFormField>

                <UFormField
                    label="Password"
                    required
                    :error="form.errors.password"
                >
                    <UInput
                        required
                        type="password"
                        v-model="form.password"
                        class="w-full"
                        placeholder="Password"
                    />
                </UFormField>

                <UFormField
                    label="Confirm password"
                    required
                    :error="form.errors.password_confirmation"
                >
                    <UInput
                        required
                        type="password"
                        v-model="form.password_confirmation"
                        class="w-full"
                        placeholder="Confirm password"
                    />
                </UFormField>

                <div class="flex items-center gap-4">
                    <UButton
                        type="submit"
                        :disabled="form.processing"
                        :loading="form.processing"
                        label="Save Password"
                    />
                </div>
            </form>
        </SettingsWrapper>
    </AdminLayout>
</template>

<script setup lang="ts">
import AdminLayout from '@modules/Dashboard/resources/layouts/AdminLayout.vue';
import SettingsWrapper from '@modules/Auth/resources/components/SettingsWrapper.vue';
import { BreadcrumbItem } from '@nuxt/ui';

const breadcrumbs: BreadcrumbItem[] = [
    {
        label: 'Password settings',
        icon: 'ph:password'
    },
];

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

function submit() {
    form.put(route('admin.password.update'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset()
        },
        onError: (errors: any) => {
            if (errors.password) {
                form.reset('password', 'password_confirmation');
            }
            if (errors.current_password) {
                form.reset('current_password');
            }
        },
    });
};
</script>

<style scoped></style>