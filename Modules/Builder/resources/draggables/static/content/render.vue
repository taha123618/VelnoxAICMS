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
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
const { element } = defineProps<{
    element: ZioraElement;
}>();


</script>

<style scoped></style>
