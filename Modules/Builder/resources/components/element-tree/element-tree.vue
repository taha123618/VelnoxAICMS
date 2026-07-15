<template>
    <div
        ref="el"
        class="dark"
    >
        <UTree
            v-model:expanded="expandedItems"
            :ui="{
                listWithChildren: 'ms-2 border-s border-default',
                itemWithChildren: 'ps-2 -ms-px',
                link: 'before:bg-transparent hover:not-disabled:before:bg-transparent px-0 py-0.5',
            }"
            multiple
            color="neutral"
            label-key="name"
            value-key="id"
            :items="store.elements"
        >
            <template #item="{ item, index, level, expanded, selected }">
                <!-- prettier-ignore -->
                <ElementTreeItem
                    :element="(item as ZioraElement)"
                    :selected="selected"
                    :index="index"
                    :level="level"
                    v-model="expandedItems"
                    :parent-item="(findParentFromId(store.elements, item.id) as ZioraElement)"
                    :expanded="expanded"
                />
            </template>
        </UTree>
    </div>
</template>

<script setup lang="ts">
import { findParentFromId, getAncestors } from '@modules/Builder/resources/scripts/factory';
import ElementTreeItem from '@modules/Builder/resources/components/element-tree/element-tree-item.vue';
import {
    type Instruction,
    extractInstruction,
} from '@atlaskit/pragmatic-drag-and-drop-hitbox/tree-item';
import { combine } from '@atlaskit/pragmatic-drag-and-drop/combine';
import { monitorForElements } from '@atlaskit/pragmatic-drag-and-drop/element/adapter';
import { TElement } from '@modules/Builder/resources/scripts/types';
import { useTree } from '@modules/Builder/resources/scripts/use-tree';
import { useZiora } from '@modules/Builder/resources/scripts/use-ziora';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';

const { updateTree } = useTree();
const el = useTemplateRef<HTMLElement>('el');
const store = useZiora();

const expandedItems = ref([store.elements[0]?.id]);

watch(() => store.selectedElement, (newValue) => {
    if (newValue) {
        let ancestors = getAncestors([...store.elements], newValue.id) || [];

        if (newValue.hasChildren()) {
            ancestors = [...ancestors, newValue.id]
        }
        for (const i of ancestors) {
            if (!expandedItems.value.includes(i)) {
                expandedItems.value = [...expandedItems.value, i]
            }
        }
    }
})

watchEffect((onCleanup) => {
    const dndFunction = combine(
        monitorForElements({
            canMonitor({ source }) {
                return source.data?.draggableType == 'TREE';
            },
            onDrop(args) {
                const { location, source } = args;
                // didn't drop on anything
                if (!location.current.dropTargets.length) return;
                const itemId = source.data.id as string;
                const target = location.current.dropTargets[0];
                const targetId = target.data.id as string;
                const instruction: Instruction | null = extractInstruction(
                    target.data,
                );
                if (instruction !== null) {
                    const updatedTree =
                        updateTree([...store.elements], {
                            type: 'instruction',
                            instruction,
                            itemId,
                            targetId,
                        }) ?? [];
                    if (updateTree.length > 0) {
                        store.setElements([
                            ZioraElement.fromObject(
                                updatedTree[0] as TElement,
                            ),
                        ]);
                    }
                }
            },
        }),
    );

    onCleanup(() => {
        dndFunction();
    });
});
</script>

<style scoped></style>
