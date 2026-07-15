<template>
    <AuthLayout
        title="Forgot password"
        description="Enter your email to receive a password reset link"
    >
        <UAlert
            v-if="status"
            class="mb-4"
            color="success"
            variant="outline"
            :description="status"
        />
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

            <UButton
                :disabled="form.processing"
                :loading="form.processing"
                block
                type="submit"
                :tabindex="2"
                label="Email password reset link"
            />

            <div class="text-center text-sm text-muted-foreground">
                Or, return to
                <Link
                    class="text-primary hover:underline"
                    :tabindex="3"
                    :href="route('admin.login')"
                >
                login
                </Link>
            </div>
        </form>
    </AuthLayout>
</template>

<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import AuthLayout from '../layouts/AuthLayout.vue';

defineProps<{
    status?: string;
}>();

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('admin.password.email'));
};
</script>

<style scoped></style>
