<template>
    <AuthLayout
        title="Reset password"
        description="Please enter your new password below"
    >
        <form
            @submit.prevent="submit"
            class="flex flex-col gap-4"
        >
            <UFormField
                label="Email address"
                required
                :error="form.errors.email"
            >
                <UInput
                    :tabindex="1"
                    autofocus
                    type="email"
                    v-model="form.email"
                    placeholder="email@example.com"
                    class="w-full"
                />
            </UFormField>

            <UFormField
                label="Password"
                required
                :error="form.errors.password"
            >
                <UInput
                    required
                    :tabindex="2"
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
                    :tabindex="3"
                    type="password"
                    v-model="form.password_confirmation"
                    class="w-full"
                    placeholder="Confirm password"
                />
            </UFormField>

            <UButton
                type="submit"
                :disabled="form.processing"
                :loading="form.processing"
                block
                :tabindex="4"
                label="Reset password"
            />
        </form>
    </AuthLayout>
</template>

<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import AuthLayout from '../layouts/AuthLayout.vue';

const { email, token } = defineProps<{
    email: string;
    token: string;
}>();

const form = useForm({
    token: token,
    email: email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('admin.password.store'), {
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        },
    });
};
</script>

<style scoped></style>
