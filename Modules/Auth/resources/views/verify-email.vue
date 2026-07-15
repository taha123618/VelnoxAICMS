<template>
    <AuthLayout
        title="Verify email"
        description="Please verify your email address by clicking on the link we just emailed to you."
    >
        <UAlert
            v-if="status === 'verification-link-sent'"
            class="mb-4"
            color="success"
            variant="outline"
            description="A new verification link has been sent to the email address you provided during registration."
        />
        <form
            @submit.prevent="submit"
            class="flex flex-col gap-4"
        >

            <UButton
                type="submit"
                :disabled="form.processing"
                :loading="form.processing"
                block
                label="Resend verification email"
            />

            <div class="text-center text-sm text-muted-foreground">
                <Link
                    class="text-primary hover:underline"
                    :href="route('admin.logout')"
                    method="post"
                    as="button"
                >
                Logout
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

const form = useForm({});

const submit = () => {
    form.post(route('admin.verification.send'));
};
</script>

<style scoped></style>
