<template>
    <BaseElementWrapper :element="element">
        <div class="container @sm:grid-cols-2">
            <PostCard
                v-for="post in posts"
                :key="post.id"
                :post="post"
                :element="element"
            />
        </div>
    </BaseElementWrapper>
</template>

<script setup lang="ts">
import BaseElementWrapper from '@modules/Builder/resources/components/base-element-wrapper.vue';
import PostCard from '@modules/Builder/resources/draggables/components/post-list/components/post-card.vue';
import { usePostList } from '@modules/Builder/resources/draggables/components/post-list/use-post-list';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import { useHead } from '@unhead/vue';

// https://dribbble.com/shots/10711483-Blog-Listing-Articles-Page
// https://dribbble.com/shots/17946180-blog-list
// https://dribbble.com/shots/25761719-Paynext-Blog-Page-Fintech-Website-UI-Figma
const { element } = defineProps<{
    element: ZioraElement;
}>();

const { getStyles, posts } = usePostList(element)

useHead({
    style: [
        {
            textContent: computed(() => getStyles()),
            id: element.id,
        },
    ],
});
</script>

<style scoped></style>
