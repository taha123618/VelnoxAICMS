<template>
    <div
        id="__box"
        class="h-full w-full bg-transparent">
        <!-- prettier-ignore -->
        <BaseRecursiveElement
                v-for="child in element.children"
                :key="child.id"
                :element="child" />
    </div>
</template>

<script setup lang="ts">
import BaseRecursiveElement from '@modules/Builder/resources/components/base-recursive-element.vue';
import type VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';

const { element } = defineProps<{
    element: VelnoxAIElement;
}>();
</script>

<style scoped></style>
