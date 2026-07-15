<template>
    <BaseContextMenu :element="element">
        <component
            :is="tag"
            ref="elRef"
            v-bind="$attrs"
            :id="element.id"
            @click.stop="isEditable ? store.setSelectedElement(element) : null"
            @mouseover.stop="isHovering = true"
            @mouseout.stop="isHovering = false"
            :class="cn(
                [
                    className,
                    {
                        '!outline-primary':
                            (isHovering || isSelected) &&
                            store.showOutline &&
                            isEditable,
                        '!bg-primary/20':
                            isDraggedOver &&
                            element.canDrop &&
                            !showIndicator &&
                            isEditable,
                        '!relative opacity-50 !ring-red-600': isDragging,
                        'flex-container static !_relative': !element.hasContent(),
                    },
                ],
                customClassNames,
                animationClass,
            )
                "
            class="builder-element box-border outline-2 outline-offset-0 outline-transparent"
        >

            <slot />

            <BaseDropIndicator
                v-if="showIndicator && isEditable"
                :edge="elState.closestEdge"
            />

            <Teleport
                v-if="elState.type === 'preview' && isEditable"
                :to="elState.container"
            >
                <div class="rounded border bg-white p-2">
                    {{ element.name }}
                </div>
            </Teleport>
        </component>
        <div
            v-if="isEditable"
            ref="floatingRef"
            :style="floatingStyles"
            :class="[
                isSelected ? 'block' : 'hidden',
                'z-50',
            ]"
        >
            <BaseDraggableHandle
                ref="dragHandle"
                :element="element"
            />
        </div>
    </BaseContextMenu>
</template>

<script setup lang="ts">
import { cn } from '@modules/Builder/resources/scripts/utils';
import BaseDropIndicator from '@modules/Builder/resources/components/base-drop-indicator.vue';
import BaseContextMenu from '@modules/Builder/resources/components/base-context-menu.vue';
import BaseDraggableHandle from '@modules/Builder/resources/components/base-draggable-handle.vue';
import { combine } from '@atlaskit/pragmatic-drag-and-drop/combine';
import { useDnD } from '@modules/Builder/resources/scripts/use-dnd';
import { useElement } from '@modules/Builder/resources/scripts/use-element';
import { useZiora } from '@modules/Builder/resources/scripts/use-ziora';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import { unrefElement } from '@vueuse/core';

const { element, tag = 'div' } = defineProps<{
    tag?: string;
    element: ZioraElement;
}>();

const store = useZiora();

const {
    isDraggedOver,
    dragCall,
    dropCall,
    elState,
    elRef,
    floatingRef,
    floatingStyles,
    isDragging,
    showIndicator,
} = useDnD(element);

const {
    customClassNames,
    isEditable,
    animationClass,
    className,
    isHovering,
    isSelected,
} = useElement(element);


const dragHandle = ref<InstanceType<typeof BaseDraggableHandle> | null>(null);

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
