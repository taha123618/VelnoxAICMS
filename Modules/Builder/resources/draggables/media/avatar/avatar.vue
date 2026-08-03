<template>
    <BaseElementWrapper :element="element">
        <UAvatar
            :src="element.getProp('src')"
            :alt="element.getProp('alt')"
            :text="element.getProp('text')"
            :ui="{
                root: `size-full rounded-[inherit]`,
            }"
        />
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
