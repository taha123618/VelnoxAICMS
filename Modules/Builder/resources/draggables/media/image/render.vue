<template>
    <component
        :id="element.id"
        :class="cn([className, customClassNames, animationClass, 'overflow-hidden'])
            "
        :is="tag"
        :href="isLink ? element.getContent('href') : null"
        :target="isLink ? element.getContent('target') : null"
    >
        <div class="relative -z-10 size-full overflow-hidden outline-none">
            <img
                :src="src"
                :style="{objectFit: element.getProp('content.objectFit')}"
                class="size-full" />
        </div>
    </component>
</template>

<script setup lang="ts">
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';
import { cn } from '@modules/Builder/resources/scripts/utils';
import { useElement } from '@modules/Builder/resources/scripts/use-element';

const { element } = defineProps<{
    element: VelnoxAIElement;
}>();

const isLink = computed<boolean>(() => {
    if (
        element.getContent('linkType') != 'none' &&
        !!element.getContent('href')
    ) {
        return true;
    }
    return element.getProp('tag') == 'a';
});

const tag = computed<string | null>(() => {
    if (
        element.getContent('linkType') != 'none' &&
        !!element.getContent('href')
    ) {
        return 'a';
    }
    return element.getProp('tag');
});

const src = computed<string>(() => {
    return (element.getContent('src') || '') as string;
});

const { customClassNames, animationClass, className } = useElement(element);
</script>

<style scoped></style>
