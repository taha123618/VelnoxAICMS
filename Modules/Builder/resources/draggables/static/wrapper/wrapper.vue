<template>
    <BaseContextMenu
        :element="element"
        :disabled="!store.enabled"
    >
        <div
            id="__wrapper"
            class="_relative min-h-screen bg-white"
            :class="cn(className, customClassNames, animationClass)"
        >
            <BaseRecursiveElement
                v-for="child in element.children"
                :key="child.id"
                :element="child"
            />

            <UAlert
                ref="elRef"
                :ui="{
                    wrapper: 'p-4',
                    icon: 'mx-auto'
                }"
                class="text-center w-full my-4 max-w-4xl mx-auto static"
                :variant="isDraggedOver ? 'solid' : 'subtle'"
                color="primary"
            >
                <template #title>
                    <div class="flex flex-col gap-2 items-center justify-center">
                        <UIcon
                            name="ph:layout"
                            class="size-12 text-primary-300"
                        />
                        <p>Drop page elements here</p>
                    </div>
                </template>
            </UAlert>
        </div>
    </BaseContextMenu>
</template>

<script setup lang="ts">
import BaseContextMenu from '@modules/Builder/resources/components/base-context-menu.vue';
import BaseRecursiveElement from '@modules/Builder/resources/components/base-recursive-element.vue';
import type ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import { cn } from '@modules/Builder/resources/scripts/utils';
import { useDnD } from '@modules/Builder/resources/scripts/use-dnd';
import { useElement } from '@modules/Builder/resources/scripts/use-element';
import { useZiora } from '@modules/Builder/resources/scripts/use-ziora';
import { unrefElement } from '@vueuse/core';
import { onMounted, onUnmounted } from 'vue';

const { element } = defineProps<{
    element: ZioraElement;
}>();

const { elRef, dropCall, isDraggedOver } = useDnD(element);
const store = useZiora();
const { animationClass, className, customClassNames } = useElement(element);

let cleanup = () => { };

onMounted(() => {
    const currentElement = unrefElement(elRef);
    if (!currentElement) return;
    cleanup = dropCall(currentElement);
});

onUnmounted(() => {
    cleanup();
});
</script>

<style scoped></style>
