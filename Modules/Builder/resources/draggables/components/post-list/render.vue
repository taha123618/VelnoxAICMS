<template>
    <div
        :class="cn(className, customClassNames, animationClass)"
        class="builder-element z-0 box-border outline-2 outline-offset-0 outline-transparent"
    >
        <div class="container @sm:grid-cols-2">
            <PostCard
                v-for="post in posts"
                :key="post.id"
                :post="post"
                :element="element"
            />
        </div>
    </div>
</template>

<script setup lang="ts">
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';
import { cn } from '@modules/Builder/resources/scripts/utils';
import { useElement } from '@modules/Builder/resources/scripts/use-element';
import { useHead } from '@unhead/vue';
import { usePostList } from '@modules/Builder/resources/draggables/components/post-list/use-post-list';
import PostCard from '@modules/Builder/resources/draggables/components/post-list/components/post-card.vue';

const { element } = defineProps<{
    element: VelnoxAIElement;
}>();

const { customClassNames, animationClass, className } = useElement(element);

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
