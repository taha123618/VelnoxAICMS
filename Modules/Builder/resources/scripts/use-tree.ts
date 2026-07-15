import type { Instruction } from '@atlaskit/pragmatic-drag-and-drop-hitbox/tree-item';
import {
    extractIds,
    findElement,
    getChildItems,
    hasChildren,
    insertChildElement,
    insertElementAfter,
    insertElementBefore,
    removeElement,
} from '@modules/Builder/resources/scripts/factory';
export type TreeAction =
    | {
          type: 'instruction';
          instruction: Instruction;
          itemId: string;
          targetId: string;
      }
    | {
          type: 'toggle';
          itemId: string;
      }
    | {
          type: 'expand';
          itemId: string;
      }
    | {
          type: 'collapse';
          itemId: string;
      }
    | { type: 'modal-move'; itemId: string; targetId: string; index: number };

export const useTree = () => {
    const customLinkTargetOptions = [
        { value: '_self', label: 'Self' },
        { value: '_blank', label: 'New window' },
    ];

    function getPathToItem({
        current,
        targetId,
        parentIds = [],
    }: {
        current: Record<string, any>[];
        targetId: string;
        parentIds?: any;
    }): string[] | undefined {
        for (const item of current) {
            if (item.id === targetId) return parentIds;
            const nested = getPathToItem({
                current: item.children ?? [],
                targetId,
                parentIds: [...parentIds, item.id],
            });
            if (nested) return nested;
        }
    }

    function updateTree(data: Record<string, any>[], action: TreeAction) {
        const item = findElement(data, action.itemId);

        if (!item) {
            return data;
        }

        if (action.type === 'instruction') {
            const instruction = action.instruction;

            if (instruction.type === 'reparent') {
                const path = getPathToItem({
                    current: data,
                    targetId: action.targetId,
                });
                if (!path) {
                    console.error(`missing ${path}`);
                    return;
                }

                const desiredId = path[instruction.desiredLevel];
                let result = removeElement(data, action.itemId);
                result = insertElementAfter(result, desiredId, item);
                return result;
            }

            // the rest of the actions require you to drop on something else
            if (action.itemId === action.targetId) return data;

            if (instruction.type === 'reorder-above') {
                let result = removeElement(data, action.itemId);
                result = insertElementBefore(result, action.targetId, item);
                return result;
            }

            if (instruction.type === 'reorder-below') {
                let result = removeElement(data, action.itemId);
                result = insertElementAfter(result, action.targetId, item);
                return result;
            }

            if (instruction.type === 'make-child') {
                let result = removeElement(data, action.itemId);
                result = insertChildElement(result, action.targetId, item);
                return result;
            }

            console.warn('TODO: action not implemented', instruction);

            return data;
        }

        if (action.type === 'modal-move') {
            let result = removeElement(data, item.id);

            const siblingItems = getChildItems(result, action.targetId) ?? [];

            if (siblingItems.length === 0) {
                if (action.targetId === '') {
                    /**
                     * If the target is the root level, and there are no siblings, then
                     * the item is the only thing in the root level.
                     */
                    result = [item];
                } else {
                    /**
                     * Otherwise for deeper levels that have no children, we need to
                     * use `insertChild` instead of inserting relative to a sibling.
                     */
                    result = insertChildElement(result, action.targetId, item);
                }
            } else if (action.index === siblingItems.length) {
                const relativeTo = siblingItems[siblingItems.length - 1];
                /**
                 * If the position selected is the end, we insert after the last item.
                 */
                result = insertElementAfter(result, relativeTo.id, item);
            } else {
                const relativeTo = siblingItems[action.index];
                /**
                 * Otherwise we insert before the existing item in the given position.
                 * This results in the new item being in that position.
                 */
                result = insertElementBefore(result, relativeTo.id, item);
            }

            return result;
        }

        return data;
    }

    return {
        extractIds,
        hasChildren,
        updateTree,
        customLinkTargetOptions,
    };
};
