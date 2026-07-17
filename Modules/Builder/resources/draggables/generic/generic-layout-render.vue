<template>
    <component
        :is="element.props.tag || 'div'"
        ref="elRef"
        v-bind="elementAttributes"
        :class="cn(className, customClassNames, animationClass)"
        class="builder-element z-0 box-border outline-2 outline-offset-0 outline-transparent">
        <BaseRecursiveElement
            v-for="child in element.children"
            :key="child.id"
            :element="child" />
    </component>
</template>

<script setup lang="ts">
import BaseRecursiveElement from '@modules/Builder/resources/components/base-recursive-element.vue';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import { cn } from '@modules/Builder/resources/scripts/utils';
import { useElement } from '@modules/Builder/resources/scripts/use-element';
import { computed } from 'vue';

const { element } = defineProps<{
    element: ZioraElement;
}>();

const { customClassNames, animationClass, className } =
    useElement(element);

const elementAttributes = computed(() => {
    const attrs: Record<string, any> = {};
    
    if (element.props.href) attrs.href = element.props.href;
    if (element.props.target) attrs.target = element.props.target;
    if (element.props.src) attrs.src = element.props.src;
    if (element.props.alt) attrs.alt = element.props.alt;
    
    // Custom attributes (aria and data attributes)
    const customAttrs = element.getProp('custom.attributes') || {};
    return { ...attrs, ...customAttrs };
});
</script>

<style scoped></style>
