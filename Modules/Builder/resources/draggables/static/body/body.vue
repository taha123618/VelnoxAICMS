<template>
    <div
        id="el__body"
        class="min-h-screen w-full bg-white"
        :class="cn(className, customClassNames, animationClass)"
    >

        <!-- prettier-ignore -->
        <BaseRecursiveElement
            v-for="child in element.children"
            :key="child.id"
            :element="child"
        />
        
        <UAlert
            ref="elRef"
            :ui="{
                wrapper: 'p-4',
                icon: 'text-red-400 mx-auto'
            }"
            class="text-center w-full my-4 max-w-4xl mx-auto"
            v-if="isEditable"
            :variant="isDraggedOver ? 'solid' : 'subtle'"
            color="primary"
        >
            <template #title>
                <div class="flex flex-col gap-2 items-center justify-center">
                    <UIcon
                        name="ph:layout"
                        class="size-12 text-primary-300"
                    />
                    <p>Drop layout elements here</p>
                </div>
            </template>
        </UAlert>

       
    </div>
</template>

<script setup lang="ts">
import { cn } from '@modules/Builder/resources/scripts/utils';
import { useDnD } from '@modules/Builder/resources/scripts/use-dnd';
import { useElement } from '@modules/Builder/resources/scripts/use-element';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import { unrefElement } from '@vueuse/core';
import BaseRecursiveElement from '@modules/Builder/resources/components/base-recursive-element.vue';

const { element } = defineProps<{
    element: ZioraElement;
}>();


const { elRef, dropCall, isDraggedOver } = useDnD(element);

const { animationClass, isEditable, className, customClassNames } =
    useElement(element);

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
