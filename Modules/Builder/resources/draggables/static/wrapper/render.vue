<template>
    <div
        id="__wrapper_render"
        :class="cn(className, customClassNames, animationClass, 'overflow-hidden')">
        <BaseRecursiveElement
            v-for="child in element.children"
            :key="child.id"
            :element="child" />
    </div>
</template>

<script setup lang="ts">
import BaseRecursiveElement from '@modules/Builder/resources/components/base-recursive-element.vue';
import type ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import { cn } from '@modules/Builder/resources/scripts/utils';
import { useElement } from '@modules/Builder/resources/scripts/use-element';

const { element } = defineProps<{
    element: ZioraElement;
}>();

const { animationClass, className, customClassNames } = useElement(element);

</script>

<style scoped></style>
