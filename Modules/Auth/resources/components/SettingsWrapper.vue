<template>
    <div>
        <SettingsHeading
            title="Settings"
            description="Manage your profile and account settings"
        />

        <div class="flex flex-col space-y-6 md:space-y-0 lg:flex-row lg:space-y-0 lg:space-x-8">
            <UNavigationMenu
                orientation="horizontal"
                :items="items"
                variant="link"
                class="w-full block lg:hidden max-w-xl"
            />
            <UNavigationMenu
                orientation="vertical"
                color="primary"
                variant="link"
                :items="items"
                class="hidden lg:block max-w-xl data-[orientation=vertical]:w-60"
            />
            <UCard :ui="{
                root: 'flex-1 md:max-w-2xl border-0',
                body: 'max-w-xl space-y-12'
            }">
                <slot />
            </UCard>
        </div>
    </div>
</template>

<script setup lang="ts">
import { NavigationMenuItem } from '@nuxt/ui';
import SettingsHeading from '@modules/Auth/resources/components/SettingsHeading.vue';

const items = computed<NavigationMenuItem[]>(() => [
    {
        label: 'Profile settings',
        to: '/cp/settings/profile',
        icon: 'ph:user-circle-gear-bold',
        active: route().current('admin.profile.edit'),
    },
    {
        label: 'Password',
        to: '/cp/settings/password',
        icon: 'ph:password-bold',
        active: route().current('admin.password.edit'),
    }
])
</script>

<style scoped></style>