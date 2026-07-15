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
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';

const { element } = defineProps<{
    element: ZioraElement;
}>();

const src = computed<string>(() => {
    return (element.getContent('src') || '') as string;
});

</script>

<style scoped></style>
