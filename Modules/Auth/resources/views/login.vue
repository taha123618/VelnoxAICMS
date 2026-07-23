<template>
    <AuthLayout
        title="Login"
        description="Enter your email and password below to log in"
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
                    required
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
                <template #hint>
                    <Link
                        class="hover:text-primary-500 hover:underline text-xs"
                        tabindex="5"
                        v-if="canResetPassword"
                        :href="route('admin.password.request')"
                    >
                    Forgot password?
                    </Link>
                </template>
                <UInput
                    required
                    :tabindex="2"
                    type="password"
                    v-model="form.password"
                    class="w-full"
                    placeholder="Password"
                />
            </UFormField>

            <UCheckbox
                :tabindex="3"
                v-model="form.remember"
                label="Remember me"
            />

            <UButton
                type="submit"
                :disabled="form.processing"
                :loading="form.processing"
                block
                :tabindex="5"
                label="Submit"
            />

            <div class="text-center text-sm text-muted-foreground">
                <UButton
                    variant="link"
                    as-child
                >
                    <Link
                        :tabindex="6"
                        :href="route('admin.register')"
                    >
                    Create Account
                    <UIcon name="ph:arrow-right" />
                    </Link>
                </UButton>
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
    canResetPassword: boolean;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('admin.login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<style scoped></style>
