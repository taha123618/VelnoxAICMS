<template>
    <div class="h-full">
        <div class="flex h-full flex-col">
            <div class="relative h-2/3">
                <UCard
                    :ui="{
                        root: 'rounded-none',
                        header: 'p-2 sm:px-2',
                        body: 'p-2 sm:p-2',
                        footer: 'p-2 sm:px-2',
                    }"
                    class="dark"
                >
                    <template #header>
                        <div class="flex items-center justify-between">
                            <h1>Site pages</h1>
                            <BaseTooltip content="New page">
                                <UButton size="xs">
                                    <ModalLink
                                        @close="
                                            router.reload({ only: ['pages'] })
                                            "
                                        :href="route('admin.pages.create')"
                                    >
                                        <UIcon name="ph:plus" />
                                    </ModalLink>
                                </UButton>
                            </BaseTooltip>
                        </div>
                    </template>
                    <ul class="space-y-2">
                        <li
                            v-for="page in pages"
                            :key="page.id"
                        >
                            <BaseTooltip content="Edit">
                                <UButton
                                    class="border-builder-inverted/10 w-full cursor-pointer rounded-md border"
                                    size="xs"
                                    trailing-icon="ph:note-pencil"
                                    :color="'neutral'"
                                    :variant="currentPage &&
                                            page.id == currentPage?.id
                                            ? 'soft'
                                            : 'link'
                                        "
                                >
                                    <Link
                                        class="block w-full text-start text-sm"
                                        :href="route('admin.pages.edit', {
                                            page: page.id,
                                        })"
                                    >
                                        {{ page.title }}
                                    </Link>
                                </UButton>
                            </BaseTooltip>
                        </li>
                    </ul>
                </UCard>
            </div>

            <div class="relative h-1/3">
                <UCard
                    :ui="{
                        root: 'rounded-none',
                        header: 'p-2 sm:px-2',
                        body: 'p-2 sm:p-2',
                        footer: 'p-2 sm:px-2',
                    }"
                    class="dark"
                >
                    <template #header>
                        <div class="flex items-center justify-between">
                            <h1>Site layouts</h1>
                            <BaseTooltip content="New layout">
                                <UButton size="xs">
                                    <ModalLink
                                        @close="
                                            router.reload({ only: ['pages'] })
                                            "
                                        :href="route('admin.layouts.create')"
                                    >
                                        <UIcon name="ph:plus" />
                                    </ModalLink>
                                </UButton>
                            </BaseTooltip>
                        </div>
                    </template>

                    <ul class="space-y-2">
                        <li
                            v-for="layout in layouts"
                            :key="layout.id"
                        >
                            <BaseTooltip content="Edit">
                                <UButton
                                    class="border-inverted/10 w-full cursor-pointer rounded-md border"
                                    size="sm"
                                    trailing-icon="ph:note-pencil"
                                    :color="'neutral'"
                                    :variant="layout.id ==
                                            (currentPage?.layoutId ||
                                                currentLayout?.id)
                                            ? 'soft'
                                            : 'link'
                                        "
                                >
                                    <Link
                                        class="block w-full text-start text-sm"
                                        :href="route('admin.layouts.edit', {
                                            layout: layout.id,
                                        })"
                                    >
                                        {{ layout.name }}
                                    </Link>
                                </UButton>
                            </BaseTooltip>
                        </li>
                    </ul>
                </UCard>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import BaseTooltip from '@modules/Builder/resources/components/base-tooltip.vue';
import { router, Link } from '@inertiajs/vue3';
import { ModalLink } from '@inertiaui/modal-vue';

const page = usePage();

const currentPage = computed<Modules.Page.Data.PageData>(
    () => page.props.page as Modules.Page.Data.PageData,
);

const currentLayout = computed<Modules.Layout.Data.LayoutData>(
    () => page.props.layout as Modules.Layout.Data.LayoutData,
);

const layouts = computed<Modules.Layout.Data.LayoutData>(
    () => page.props.layouts as Modules.Layout.Data.LayoutData,
);

const pages = computed<Modules.Page.Data.PageData>(
    () => page.props.pages as Modules.Page.Data.PageData,
);
</script>

<style scoped></style>
