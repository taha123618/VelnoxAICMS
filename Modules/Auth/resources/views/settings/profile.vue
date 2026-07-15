<template>
    <AdminLayout :breadcrumbs="breadcrumbs">

        <Head title="Manage Profile" />
        <SettingsWrapper>
            <form
                class="space-y-4"
                @submit.prevent="submit"
            >
                <UFormField
                    label="First name"
                    required
                    :error="form.errors.first_name"
                >
                    <UInput
                        autofocus
                        v-model="form.first_name"
                        class="w-full"
                    />
                </UFormField>

                <UFormField
                    label="Last name"
                    required
                    :error="form.errors.last_name"
                >
                    <UInput
                        v-model="form.last_name"
                        class="w-full"
                    />
                </UFormField>

                <UFormField
                    label="Email address"
                    required
                    :error="form.errors.email"
                >
                    <UInput
                        type="email"
                        v-model="form.email"
                        placeholder="email@example.com"
                        class="w-full"
                    />
                </UFormField>

                <div v-if="mustVerifyEmail && !user.isVerified">
                    <p class="text-sm text-muted-foreground">
                        Your email address is unverified.
                        <UButton
                            variant="link"
                            as-child
                            class="px-0"
                        >
                            <Link
                                :href="route('admin.verification.send')"
                                method="post"
                                as="button"
                            >
                            Click here to resend the verification email.
                            </Link>
                        </UButton>
                    </p>
                    <UAlert
                        :ui="{
                            root: 'p-2.5'
                        }"
                        v-if="status === 'verification-link-sent'"
                        class="mb-4"
                        color="success"
                        variant="soft"
                        description="A new verification link has been sent to your email address."
                    />
                </div>

                <div class="flex items-center gap-4">
                    <UButton
                        type="submit"
                        :disabled="form.processing || !form.isDirty"
                        :loading="form.processing"
                        label="Save changes"
                    />
                </div>

            </form>
        </SettingsWrapper>
    </AdminLayout>

</template>

<script setup lang="ts">
import AdminLayout from '@modules/Dashboard/resources/layouts/AdminLayout.vue';
import SettingsWrapper from '@modules/Auth/resources/components/SettingsWrapper.vue';
import { useAuth } from '@modules/Auth/resources/composables/use-auth';
import { BreadcrumbItem } from '@nuxt/ui';

const user = useAuth()

const breadcrumbs: BreadcrumbItem[] = [
    {
        label: 'Profile Settings',
        icon: 'ph:user-circle-gear'
    },
];

defineProps<{
    mustVerifyEmail: boolean;
    status?: string;
}>();


const form = useForm({
    first_name: user.value.first_name,
    last_name: user.value.last_name,
    email: user.value.email,
    avatar: user.value.avatar,
});

function submit() {
    form.patch(route('admin.profile.update'), {
        preserveScroll: true
    });
};
</script>

<style lang="scss" scoped></style>