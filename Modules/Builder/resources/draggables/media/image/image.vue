<template>
    <BaseElementWrapper :element="element">
        <div class="relative size-full overflow-hidden outline-none">
            <img
                :src="src"
                :style="{objectFit: element.getProp('content.objectFit')}"
                class="size-full" />
        </div>
    </BaseElementWrapper>
</template>

<script setup lang="ts">
import BaseElementWrapper from '@modules/Builder/resources/components/base-element-wrapper.vue';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';

const { element } = defineProps<{
    element: VelnoxAIElement;
}>();

const src = computed<string>(() => {
    return (element.getContent('src') || '') as string;
});

</script>

<style scoped></style>
