<template>
    <div class="relative">
        <BaseRecursiveElement
            v-for="child in element.children"
            :key="child.id"
            :element="child"
        />
    </div>
</template>

<script setup lang="ts">
import BaseRecursiveElement from '@modules/Builder/resources/components/base-recursive-element.vue';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';
const { element } = defineProps<{
    element: VelnoxAIElement;
}>();


</script>

<style scoped></style>
