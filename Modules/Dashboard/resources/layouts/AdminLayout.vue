<template>
    <UApp>
        <div class="fixed inset-0 flex overflow-hidden">
            <div
                class="hidden min-h-svh w-[var(--admin-sidebar-width)] min-w-16 shrink-0 flex-col border-r border-neutral-600 bg-neutral-900 lg:flex">
                <div
                    class="flex h-[var(--ui-header-height)] shrink-0 items-center gap-1.5 border-b border-neutral-600 px-4 text-neutral-200">
                    <img
                        src="/assets/images/logo-full-white.svg"
                        class="max-h-[var(--ui-header-height)] w-auto"
                    />
                </div>
                <div class="flex flex-1 flex-col gap-4 overflow-y-auto px-2 py-4">
                    <UNavigationMenu
                        :external-icon="false"
                        orientation="vertical"
                        :items="mainNavItems"
                        class="data-[orientation=vertical]:w-full"
                    />
                </div>
                <div class="flex shrink-0 flex-col items-center gap-2 lg:border-t lg:border-neutral-600">
                    <UDropdownMenu
                        arrow
                        :items="userMenuItems"
                        :content="{
                            align: 'center',
                            side: 'bottom',
                            sideOffset: 0,
                        }"
                        :ui="{
                            content: 'min-w-56 rounded-lg dark',
                        }"
                    >
                        <UButton
                            :ui="{
                                base: 'bg-transparent hover:bg-neutral-700 rounded-none text-white',
                                trailingIcon: 'ml-auto',
                            }"
                            :avatar="{
                                src: user.avatar as string | undefined,
                                alt: user.name,
                            }"
                            class="w-full"
                            size="xl"
                            :label="user.name"
                            color="neutral"
                            variant="ghost"
                            trailing-icon="ph:dots-three"
                        />
                    </UDropdownMenu>
                </div>
            </div>

            <main
                class="lg:not-last:border-default relative flex min-h-svh min-w-0 flex-1 flex-col bg-neutral-100 dark:bg-neutral-950 lg:not-last:border-r"
            >
                <div
                    class="border-default flex h-[var(--ui-header-height)] shrink-0 items-center justify-between gap-1.5 border-b bg-neutral-900 px-4 sm:px-6">
                    <div class="flex min-w-0 items-center gap-1.5">
                        <nav class="flex items-center gap-1.5">
                            <template v-for="(item, index) in breadcrumbs" :key="index">
                                <Link
                                    v-if="item.href"
                                    :href="item.href"
                                    class="text-base text-white hover:text-neutral-300 transition-colors"
                                >
                                    {{ item.label }}
                                </Link>
                                <span
                                    v-else
                                    class="text-base text-white"
                                >
                                    {{ item.label }}
                                </span>
                                <UIcon
                                    v-if="index < breadcrumbs.length - 1"
                                    name="ph:caret-right"
                                    class="w-4 h-4 text-neutral-400"
                                />
                            </template>
                        </nav>
                    </div>
                    <div class="flex shrink-0 text-sm items-center text-neutral-400 gap-3">
                        v{{ version }}
                    </div>
                </div>
                <div class="flex h-full flex-1 flex-col overflow-y-auto p-6 lg:p-8">
                    <slot />
                </div>
            </main>
        </div>
    </UApp>
</template>

<script setup lang="ts">
import { SharedData } from '@/types';
import { useAuth } from '@modules/Auth/resources/composables/use-auth';
import type {
    BreadcrumbItem,
    DropdownMenuItem,
    NavigationMenuItem,
} from '@nuxt/ui';
import { useNotification } from '@/composables/use-notification';
import { router, Link } from '@inertiajs/vue3';

const user = useAuth();

const version = usePage<SharedData>().props.version

interface Props {
    breadcrumbs?: BreadcrumbItem[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

useNotification()

const mainNavItems = computed<NavigationMenuItem[]>(() => [
    {
        label: 'Home',
        icon: 'ph:house-line',
        active: route().current('admin.dashboard'),
        onSelect: () => router.visit(route('admin.dashboard')),
    },
    {
        label: 'File manager',
        icon: 'ph:image',
        active: route().current('admin.folders*'),
        onSelect: () => router.visit(route('admin.folders.index')),
    },
    {
        label: 'Pages',
        icon: 'ph:book-open-text',
        active: route().current('admin.pages*'),
        onSelect: () => router.visit(route('admin.pages.index')),
    },
    {
        label: 'Posts',
        type: 'label',
        icon: 'ph:push-pin',
        trailingIcon: 'ph:minus',
        open: true,
        active:
            route().current('admin.posts*') ||
            route().current('admin.categories*'),
        children: [
            {
                label: 'List posts',
                active: route().current('admin.posts*'),
                onSelect: () => router.visit(route('admin.posts.index')),
            },
            {
                label: 'Categories',
                active: route().current('admin.categories*'),
                onSelect: () => router.visit(route('admin.categories.index')),
            },
        ],
    },
    {
        label: 'Site Structure',
        type: 'label',
        icon: 'ph:paint-brush-broad',
        open: true,
        trailingIcon: 'ph:minus',
        active: route().current('admin.layout*'),
        children: [
            {
                label: 'Layout builder',
                active: route().current('admin.layout*'),
                onSelect: () => router.visit(route('admin.layouts.index')),
            },
            {
                label: 'Menu builder',
                active: route().current('admin.menus*'),
                onSelect: () => router.visit(route('admin.menus.index')),
            },
        ],
    },

    {
        label: 'Access management',
        type: 'label',
        trailingIcon: 'ph:minus',
        icon: 'ph:shield-checkered',
        open: true,
        children: [
            {
                label: 'User management',
                active: route().current('admin.users*'),
                onSelect: () => router.visit(route('admin.users.index')),
            },
            {
                label: 'Roles & permissions',
                active: route().current('admin.roles*'),
                onSelect: () => router.visit(route('admin.roles.index')),
            },
        ],
    },
    {
        label: 'Addons',
        type: 'label',
        trailingIcon: 'ph:minus',
        icon: 'ph:nut',
        open: true,
        children: [
            {
                label: 'Testimonials',
                active: route().current('admin.testimonials*'),
                onSelect: () => router.visit(route('admin.testimonials.index')),
            },
            {
                label: 'Contact messages',
                active: route().current('admin.contacts*'),
                onSelect: () => router.visit(route('admin.contacts.index')),
            },
        ],
    },
    {
        label: 'Marketplace',
        icon: 'ph:storefront',
        active: route().current('marketplace.*'),
        onSelect: () => router.visit(route('marketplace.index')),
    },
    {
        label: 'Headless CMS',
        type: 'label',
        icon: 'ph:database',
        trailingIcon: 'ph:minus',
        open: true,
        children: [
            {
                label: 'Collections',
                active: route().current('admin.collections*'),
                onSelect: () => router.visit(route('admin.collections.index')),
            },
        ],
    },
    {
        label: 'Platform Settings',
        type: 'label',
        icon: 'ph:gear',
        trailingIcon: 'ph:minus',
        open: true,
        children: [
            {
                label: 'General Settings',
                active: route().current('settings.*'),
                onSelect: () => router.visit(route('settings.index')),
            },
            {
                label: 'API Tokens',
                active: route().current('api-tokens.*'),
                onSelect: () => router.visit(route('api-tokens.index')),
            },
            {
                label: 'Audit Log',
                active: route().current('audit-log.*'),
                onSelect: () => router.visit(route('audit-log.index')),
            },
            {
                label: 'Languages',
                active: route().current('admin.languages*'),
                onSelect: () => router.visit(route('admin.languages.index')),
            },
            {
                label: 'Webhooks',
                active: route().current('admin.webhooks*'),
                onSelect: () => router.visit(route('admin.webhooks.index')),
            },
        ],
    },
]);

const userMenuItems = ref<DropdownMenuItem[]>([
    {
        label: 'Update profile',
        icon: 'ph:user-circle-gear',
        onSelect: () => router.visit(route('admin.profile.edit')),
    },
    {
        label: 'Update password',
        icon: 'ph:password',
        onSelect: () => router.visit(route('admin.password.edit')),
    },
    {
        label: 'Logout',
        icon: 'ph:power',
        color: 'error',
        class: 'cursor-pointer',
        onSelect: () => {
            router.post(route('admin.logout'));
        },
    },
]);
</script>

<style scoped></style>
