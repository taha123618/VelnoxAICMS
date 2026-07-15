<template>
    <component
        :is="element.props.tag"
        ref="elRef"
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

const { element } = defineProps<{
    element: ZioraElement;
}>();

const { customClassNames, animationClass, className } =
    useElement(element);
</script>

<style scoped></style>
