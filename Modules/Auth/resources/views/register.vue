<template>
    <AuthLayout
        title="Create an account"
        description="Enter your details below to create your account"
    >
        <form
            @submit.prevent="submit"
            class="flex flex-col gap-4"
        >
            <div class="grid gap-4 sm:grid-cols-2">
                <UFormField
                    label="First name"
                    required
                    :error="form.errors.first_name"
                >
                    <UInput
                        :tabindex="1"
                        autofocus
                        v-model="form.first_name"
                        placeholder="First name"
                        class="w-full"
                    />
                </UFormField>

                <UFormField
                    label="Last name"
                    required
                    :error="form.errors.last_name"
                >
                    <UInput
                        :tabindex="2"
                        v-model="form.last_name"
                        placeholder="Last name"
                        class="w-full"
                    />
                </UFormField>
            </div>

            <UFormField
                label="Email address"
                required
                :error="form.errors.email"
            >
                <UInput
                    :tabindex="3"
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
                    :tabindex="4"
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
                    :tabindex="5"
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
                :tabindex="6"
                label="Create account"
            />

            <div class="text-center text-sm text-muted-foreground">
                Already have an account?
                <Link
                    class="text-primary hover:underline"
                    :tabindex="7"
                    :href="route('admin.login')"
                >
                Log in
                </Link>
            </div>
        </form>
    </AuthLayout>
</template>

<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import AuthLayout from '../layouts/AuthLayout.vue';

const form = useForm({
    first_name: '',
    last_name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('admin.register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<style scoped></style>
