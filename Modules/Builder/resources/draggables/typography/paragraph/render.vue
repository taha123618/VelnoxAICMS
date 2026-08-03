<template>
    <div
        :id="element.id"
        :class="cn(className, customClassNames, animationClass)"
        v-html="content"
        class="builder-element box-border"
    />
</template>

<script setup lang="ts">
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';
import { cn } from '@modules/Builder/resources/scripts/utils';
import { useElement } from '@modules/Builder/resources/scripts/use-element';

const { element } = defineProps<{
    element: VelnoxAIElement;
}>();

const content = computed<string>(() => {
    return (element.getContent('innerText') || '') as string;
});

const { customClassNames, animationClass, className } =
    useElement(element);
</script>

<style scoped></style>
