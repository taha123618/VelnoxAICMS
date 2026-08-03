<template>
    <div :id="element.id" :class="cn([className, customClassNames, animationClass])" class="builder-element box-border">
        <component :is="element.props.tag" :contenteditable="false" class="outline-none">
            {{ content }}
        </component>
    </div>
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

const { customClassNames, animationClass, className } = useElement(element);
</script>

<style scoped></style>
