<template>
    <component
        :id="element.id"
        :is="element.props.tag || 'div'"
        :class="cn([className, customClassNames, animationClass])"
        class="builder-element box-border grid-container"
    >
        <BaseRecursiveElement
            v-for="child in element.children"
            :key="child.id"
            :element="child"
        />
    </component>
</template>

<script setup lang="ts">
import BaseRecursiveElement from '@modules/Builder/resources/components/base-recursive-element.vue';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';
import { cn } from '@modules/Builder/resources/scripts/utils';
import { useElement } from '@modules/Builder/resources/scripts/use-element';

const { element } = defineProps<{
    element: VelnoxAIElement;
}>();

const { customClassNames, animationClass, className } = useElement(element);
</script>

<style scoped></style>
