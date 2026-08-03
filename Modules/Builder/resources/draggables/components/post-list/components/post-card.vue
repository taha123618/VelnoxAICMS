<template>
    <div class="flex flex-col gap-2 item">
        <!-- prettier-ignore -->
        <img
            v-if="element.getProp('img.show')"
            :src="(post.featuredImage as string | undefined)"
            class="object-cover img"
        />
        <div class="mt-1 flex flex-1 flex-col metabox">
            <div class="flex justify-between items-start">
                <div
                    v-if="element.getProp('title.show')"
                    class="mb-2 flex-1 line-clamp-2 text-xl font-bold title"
                >
                    {{ post.title }}
                </div>
                <UBadge
                    v-if="element.getProp('category.show')"
                    variant='soft'
                    size="sm"
                    :label="post.categoryName"
                />
            </div>
            <div
                v-if="element.getProp('excerpt.show')"
                class="mb-4 line-clamp-2 excerpt"
            >
                {{ post.excerpt }}
            </div>
            <div
                v-if="element.getProp('link.show')"
                class="mb-2"
                :class="[
                    { 'mt-auto': element.getProp('link.autoLayout') },
                ]"
            >
                <Link
                    class="link"
                    :href="post.url"
                >
                {{ element.getProp('link.text') }}
                </Link>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';

defineProps<{
    element: VelnoxAIElement,
    post: Modules.Page.Data.PostData
}>()
</script>

<style scoped></style>