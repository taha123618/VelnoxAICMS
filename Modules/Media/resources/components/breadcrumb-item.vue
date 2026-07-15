<template>
    <div ref="elRef">
        <UButton
            :variant="isDraggedOver ? 'soft' : 'link'"
            :color="isActive ? 'neutral' : 'primary'"
            :label="item.label"
            @click.prevent="emit('clicked')"
        />
    </div>
</template>

<script setup lang="ts">
import { combine } from '@atlaskit/pragmatic-drag-and-drop/combine';
import { useMedia } from '@modules/Media/resources/scripts/useMedia';
import { unrefElement } from '@vueuse/core';
const { item } = defineProps<{
    item: Record<string, any>,
    isActive: boolean | undefined
}>()

const emit = defineEmits(['clicked'])

const elRef = useTemplateRef<HTMLElement>('elRef');

const { dragCall, dropCall, isDraggedOver } = useMedia(item);


watchEffect((onCleanup) => {
    const currentElement = unrefElement(elRef);
    if (!currentElement) return;

    const dndFunction = combine(
        dragCall(currentElement, false),
        dropCall(currentElement, !item.isLast),
    )

    onCleanup(() => {
        dndFunction();
    });
})
</script>

<style scoped></style>