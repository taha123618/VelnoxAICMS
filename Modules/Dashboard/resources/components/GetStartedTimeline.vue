<template>
    <UCard class="max-w-xl dark">
        <UTimeline
            orientation="vertical"
            :items="items"
            class="w-full"
        >
            <template #layout-description="{ item }: { item: TimelineItem }">
                <p class="mb-2">{{ item.description }}</p>
                <UButton
                    v-if="authUser.can.create_layouts"
                    icon="ph:plus-circle"
                    @click="handleVisitModal(route('admin.layouts.create'))"
                    size="sm"
                    label="Create layout"
                />
            </template>
            <template #page-description="{ item }: { item: TimelineItem }">
                <p class="mb-2">{{ item.description }}</p>
                <div class="flex gap-x-2 items-center">
                    <UButton
                        v-if="authUser.can.create_pages"
                        icon="ph:plus-circle"
                        @click="handleVisitModal(route('admin.pages.create'))"
                        size="sm"
                        label="Create page"
                    />

                    <UButton
                        v-if="authUser.can.create_posts"
                        icon="ph:plus-circle"
                        @click="handleVisitModal(route('admin.posts.create'))"
                        size="sm"
                        label="Create post"
                    />
                </div>
            </template>

            <template #menu-description="{ item }: { item: TimelineItem }">
                <p class="mb-2">{{ item.description }}</p>
                <UButton
                    v-if="authUser.can.create_menus"
                    icon="ph:plus-circle"
                    @click="handleVisitModal(route('admin.menus.create'))"
                    size="sm"
                    label="Create menu"
                />
            </template>

            <template #builder-description="{ item }: { item: TimelineItem }">
                <p class="mb-2">{{ item.description }}</p>
                <UButton
                    as-child
                    size="sm"
                >
                    <Link :href="route('admin.layouts.index')">Go to layouts</Link>
                </UButton>
            </template>
        </UTimeline>
    </UCard>
</template>

<script setup lang="ts">
import { TimelineItem } from '@nuxt/ui';
import { visitModal } from '@inertiaui/modal-vue'
import { useAuth } from '@modules/Auth/resources/composables/use-auth';

const authUser = useAuth()

const emit = defineEmits<{
    (e: 'close'): void
}>()

const handleVisitModal = (url: string) => {
    emit('close')
    visitModal(url)
}

const items = ref<TimelineItem[]>([
    {
        title: 'Create a layout',
        description: "All pages must be attached to a Layout, so create a Layout first. You don't have to build the layouts yet, but create one.",
        icon: 'ph:number-one',
        slot: 'layout' as const
    },
    {
        title: 'Create Pages and/or Posts',
        description: 'Then create your Pages and Posts. each of them must be linked to a layout.',
        icon: 'ph:number-two',
        slot: 'page' as const
    },
    {
        title: 'Create a Menu',
        description: 'Now create a Menu. You will be able to attach menus to the layout when you start using the Builder.',
        icon: 'ph:number-three',
        slot: 'menu' as const
    },
    {
        title: 'Now build your pages',
        description: 'Once all the blank elements are created, you can start building. Pro tip: Begin with building the layouts.',
        icon: 'ph:number-four',
        slot: 'builder' as const
    }
])
</script>

<style scoped></style>