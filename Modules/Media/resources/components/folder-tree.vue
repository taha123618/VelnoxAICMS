<template>
    <UTree
        multiple
        selectionBehavior="replace"
        v-model:expanded="expandedTreeItems"
        :items="items"
        value-key="id"
        label-key="name"
    >
        <template #item="{ item, index, level, expanded, selected }">
            <FolderTreeItem
                :item="item"
                :index="index"
                :level="level"
                :expanded="expanded"
                :selected="selected"
                :is-modal-page="isModalPage"
                v-model="expandedTreeItems"
                class="focus:ring-primary flex items-center rounded outline-none focus:ring-2"
            />
        </template>
    </UTree>
</template>

<script setup lang="ts">
import FolderTreeItem from '@modules/Media/resources/components/folder-tree-item.vue';
import {
    type Instruction,
    extractInstruction,
} from '@atlaskit/pragmatic-drag-and-drop-hitbox/tree-item';
import { combine } from '@atlaskit/pragmatic-drag-and-drop/combine';
import { monitorForElements } from '@atlaskit/pragmatic-drag-and-drop/element/adapter';
import { useTree } from '@modules/Builder/resources/scripts/use-tree';
import { findParentFromId } from '@modules/Builder/resources/scripts/factory';

const { items, isModalPage = false } = defineProps<{
    items: Record<string, any>[];
    isModalPage?: boolean;
    handleModalReload?: any
}>()

const { updateTree } = useTree()

const expandedTreeItems = ref([items[0]?.id])

function getParentFolder(
    folderArray: Record<string, any>[],
    folderId: string,
    parent: Record<string, any> | null = null,
): Record<string, any> | null {
    for (const item of folderArray) {
        if (item.id === folderId) {
            return parent;
        }
        if (item.children.length > 0) {
            const result = findParentFromId(item.children, folderId, item);
            if (result) return result;
        }
    }
    return null;
}

watchEffect((onCleanup) => {
    const dndFunction = combine(
        monitorForElements({
            canMonitor: ({ source }) => {
                return source.data.type == 'FolderTree';
            },
            onDrop(args) {
                const { location, source } = args;
                if (!location.current.dropTargets.length) return;
                const itemId = source.data.id as string;
                const target = location.current.dropTargets[0];
                const targetId = target.data.id as string;

                const instruction: Instruction | null = extractInstruction(
                    target.data,
                );

                if (instruction !== null) {
                    const newOrder = updateTree(items, {
                        type: 'instruction',
                        instruction,
                        itemId,
                        targetId
                    }) as Record<string, any>[]

                    const newParent = getParentFolder(newOrder, itemId)

                    if (newParent && newParent.id !== source.data.parent_id) {
                        router.put(route('admin.media.folder.drag', itemId), {
                            parentId: newParent.id
                        }, {
                            onSuccess: () => {
                                useToast().add({
                                    color: 'success',
                                    description: 'Folders updated'
                                })
                            }
                        })
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