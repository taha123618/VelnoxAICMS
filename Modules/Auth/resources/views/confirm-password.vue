<template>
    <AuthLayout
        title="Confirm your password"
        description="This is a secure area of the application. Please confirm your password before continuing."
    >
        <form
            @submit.prevent="submit"
            class="flex flex-col gap-4"
        >
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

            <UButton
                type="submit"
                :disabled="form.processing"
                :loading="form.processing"
                block
                label="Confirm Password"
            />
        </form>
    </AuthLayout>
</template>

<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import AuthLayout from '../layouts/AuthLayout.vue';

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('admin.password.confirm'), {
        onFinish: () => {
            form.reset();
        },
    });
};
</script>

<style scoped></style>
