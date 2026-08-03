<template>
    <BaseElementWrapper
        :element="element"
        tag="a"
        target="_blank"
        @click.prevent
        :href="(element.getContent('href') as string)"
    >
        {{ element.getContent('innerText') }}
    </BaseElementWrapper>
</template>

<script setup lang="ts">
import BaseElementWrapper from '@modules/Builder/resources/components/base-element-wrapper.vue';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';

const { element } = defineProps<{
    element: VelnoxAIElement;
}>();

</script>

<style scoped></style>
