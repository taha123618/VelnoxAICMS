<template>
    <BaseElementWrapper
        :element="element"
        class="grid-container !relative"
    >
        <!-- prettier-ignore -->
        <BaseRecursiveElement
            v-for="child in element.children"
            :key="child.id"
            :element="child"
        />
        <div
            v-for="cell in emptyCellCounter"
            :key="cell"
            class="size-full flex opacity-75"
        >
            <div class="text-neutral-400 size-full flex border border-white/75 items-center justify-center gap-2 p-2 bg-neutral-200">
                <UIcon name="system-uicons:box-open" class="size-6 text-neutral-400" />
                <span>Empty</span>
            </div>
        </div>
    </BaseElementWrapper>
</template>

<script setup lang="ts">
import BaseElementWrapper from '@modules/Builder/resources/components/base-element-wrapper.vue';
import BaseRecursiveElement from '@modules/Builder/resources/components/base-recursive-element.vue';
import { useZiora } from '@modules/Builder/resources/scripts/use-ziora';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';

const { element } = defineProps<{
    element: ZioraElement;
}>();

const store = useZiora()

const emptyCellCounter = computed(() => element.getEmptyGridCellCount(store.device, store.currentState))


</script>

<style scoped></style>
