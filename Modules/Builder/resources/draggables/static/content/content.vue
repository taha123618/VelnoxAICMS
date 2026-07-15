<template>
    <div
        ref="elRef"
        :class="{ 'py-1': store.builderType == 'layout' }"
    >
        <UAlert
            :ui="{
                root: 'static',
                description: 'opacity-100'
            }"
            class="text-center w-full my-4 max-w-4xl mx-auto"
            v-if="store.builderType == 'layout'"
            variant="subtle"
            color="neutral"
            title="Page content will be injected here."
            description="You can drop your layout elements above and below this slot."
        />

        <!-- prettier-ignore -->
        <BaseRecursiveElement
            v-for="child in element.children"
            :key="child.id"
            :element="child"
        />

        <BaseDropIndicator
            v-if="showIndicator && isEditable"
            :edge="elState.closestEdge"
        />
    </div>
</template>

<script setup lang="ts">
import { unrefElement } from '@vueuse/core';
import { combine } from '@atlaskit/pragmatic-drag-and-drop/combine';
import { useZiora } from '@modules/Builder/resources/scripts/use-ziora';
import { useElement } from '@modules/Builder/resources/scripts/use-element';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import BaseRecursiveElement from '@modules/Builder/resources/components/base-recursive-element.vue';
import BaseDropIndicator from '@modules/Builder/resources/components/base-drop-indicator.vue';
import { useDnD } from '@modules/Builder/resources/scripts/use-dnd';

const { element } = defineProps<{
    element: ZioraElement;
}>();

const store = useZiora();

const { dragCall, dropCall, elState, elRef, showIndicator } = useDnD(element);

const { isEditable } = useElement(element);

watchEffect((onCleanup) => {
    const currentElement = unrefElement(elRef);

    if (!currentElement) return;

    const dndFunction = combine(
        dragCall(currentElement),
        dropCall(currentElement),
    );

    onCleanup(() => {
        dndFunction();
    });
});
</script>

<style scoped></style>