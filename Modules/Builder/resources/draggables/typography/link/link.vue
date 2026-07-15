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
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';

const { element } = defineProps<{
    element: ZioraElement;
}>();

</script>

<style scoped></style>
