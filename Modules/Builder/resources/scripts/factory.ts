import {
    CurrentState,
    DeviceType,
} from '@modules/Builder/resources/scripts/enums';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';

export function hasChildren(element: Record<string, any>): boolean {
    return (element.children ?? []).length > 0;
}

export function findElement(
    elementsArray: Record<string, any>[],
    elementId: string,
): Record<string, any> | undefined {
    for (const item of elementsArray) {
        if (item.id === elementId) return item;

        if (hasChildren(item)) {
            const result = findElement(item.children ?? [], elementId);
            if (result) return result;
        }
    }
}

export function removeElement(
    elementsArray: Record<string, any>[],
    elementId: string,
): Record<string, any>[] {
    return elementsArray
        .filter((item) => item.id !== elementId)
        .map((item) => {
            if (hasChildren(item)) {
                return {
                    ...item,
                    children: removeElement(item.children ?? [], elementId),
                };
            }
            return item;
        });
}

export function insertElementBefore(
    elementsArray: Record<string, any>[],
    targetElementId: string,
    newItem: Record<string, any>,
): Record<string, any>[] {
    return elementsArray.flatMap((item) => {
        if (item.id === targetElementId) return [newItem, item];

        if (hasChildren(item)) {
            return {
                ...item,
                children: insertElementBefore(
                    item.children ?? [],
                    targetElementId,
                    newItem,
                ),
            };
        }
        return item;
    });
}

export function insertElementAfter(
    elementsArray: Record<string, any>[],
    targetElementId: string,
    newItem: Record<string, any>,
): Record<string, any>[] {
    return elementsArray.flatMap((item) => {
        if (item.id === targetElementId) return [item, newItem];
        if (hasChildren(item)) {
            return {
                ...item,
                children: insertElementAfter(
                    item.children ?? [],
                    targetElementId,
                    newItem,
                ),
            };
        }
        return item;
    });
}

export function addElementToParent(
    elementsArray: Record<string, any>[],
    targetParentId: string,
    elementToInsert: Record<string, any>,
): Record<string, any>[] {
    return elementsArray.map((item) => {
        if (item.id === targetParentId && Array.isArray(item.children)) {
            item.children = [...item.children, elementToInsert];
            return item;
        }
        if (!hasChildren(item)) return item;
        item.children = addElementToParent(
            item.children,
            targetParentId,
            elementToInsert,
        );
        return item;
    });
}

export function insertChildElement(
    elementsArray: Record<string, any>[],
    targetElementId: string,
    newItem: Record<string, any>,
): Record<string, any>[] {
    return elementsArray.flatMap((item) => {
        if (item.id === targetElementId) {
            return {
                ...item,
                isOpen: true,
                children: [newItem, ...(item.children ?? [])],
            };
        }

        if (!hasChildren(item)) return item;

        return {
            ...item,
            children: insertChildElement(
                item.children ?? [],
                targetElementId,
                newItem,
            ),
        };
    });
}

export function extractIds(data: unknown) {
    const idSet = new Set<string>();

    function recurse(item: unknown) {
        if (Array.isArray(item)) {
            item.forEach(recurse);
        } else if (item && typeof item === 'object') {
            const obj = item as Record<string, any>;
            if ('id' in obj) {
                idSet.add(obj.id);
            }

            Object.values(obj).forEach(recurse);
        }
    }

    recurse(data);

    return Array.from(idSet);
}

export function findParentFromId(
    elementsArray: Record<string, any>[],
    elementId: string,
    parent: Record<string, any> | null = null,
): Record<string, any> | null {
    for (const item of elementsArray) {
        if (item.id === elementId) {
            return parent;
        }
        if (Array.isArray(item.children) && item.children.length) {
            const result = findParentFromId(item.children, elementId, item);
            if (result) return result;
        }
    }
    return null;
}

export function getAncestors(
    elementsArray: Record<string, any>[],
    elementId: string,
    parentIds: string[] = [],
): string[] | null {
    for (const node of elementsArray) {
        const newPath = [...parentIds, node.id];
        if (node.id === elementId) {
            return parentIds; // found the target, return the path
        }

        if (node.children && node.children.length > 0) {
            const result = getAncestors(node.children, elementId, newPath);
            if (result) {
                return result; // found in subtree
            }
        }
    }

    return null;
    // return []; // not found
}

// export function getAncestors(
//     elementsArray: Record<string, any>[],
//     elementId: string,
//     parentIds: string[] = [],
// ): string[] {
//     for (const item of elementsArray) {
//         if (item.id === elementId) return parentIds;
//         const nested = getAncestors(item.children, elementId, [
//             ...parentIds,
//             item.id,
//         ]);
//         if (nested) return nested;
//     }
//     return [];
// }

export function getChildItems(data: Record<string, any>[], targetId: string) {
    /**
     * An empty string represents the root
     */
    if (targetId === '') return data;

    const targetItem = findElement(data, targetId);
    if (!targetItem) {
        console.error(`missing ${targetItem}`);
        return;
    }

    return targetItem.children;
}

export function getNestedProperty(obj: Record<string, any>, path: string) {
    return path
        .split('.')
        .reduce(
            (acc, key) =>
                acc && acc[key] !== undefined ? acc[key] : undefined,
            obj,
        );
}

export function extractStyles(
    elementsArray: ZioraElement[],
    stylesMap: Record<string, any> = {},
) {
    if (!stylesMap.hasOwnProperty(DeviceType.Desktop))
        stylesMap[DeviceType.Desktop] = {};
    if (!stylesMap.hasOwnProperty(DeviceType.Tablet))
        stylesMap[DeviceType.Tablet] = {};
    if (!stylesMap.hasOwnProperty(DeviceType.Mobile))
        stylesMap[DeviceType.Mobile] = {};
    if (!stylesMap.hasOwnProperty('custom')) stylesMap['custom'] = '';

    elementsArray.forEach((element) => {
        const className = `element_${element.id}`;
        if (
            element.props.styles &&
            Object.keys(element.props.styles).length > 0
        ) {
            const styles = element.getElementStylesForRender();
            stylesMap.desktop[className] = styles.desktop;
            stylesMap.tablet[className] = styles.tablet;
            stylesMap.mobile[className] = styles.mobile;
        }

        if (element.getCustomStyle('styles')) {
            stylesMap.custom += `\n ${element.getCustomStyle('styles')}`;
        }

        if (element.children && Array.isArray(element.children)) {
            extractStyles(element.children, stylesMap);
        }
    });

    if (
        Object.keys(stylesMap[DeviceType.Desktop]).length == 0 &&
        Object.keys(stylesMap.custom).length == 0
    ) {
        return '';
    }

    let desktopCss = '';
    let desktopHoverCss = '';
    let tabletCss = '';
    let tabletHoverCss = '';
    let mobileCss = '';
    let mobileHoverCss = '';

    Object.keys(stylesMap[DeviceType.Desktop]).forEach((key) => {
        desktopCss += `.${key}{${stylesMap[DeviceType.Desktop][key][CurrentState.Default]}}`;
        desktopHoverCss += `.${key}:hover{${stylesMap[DeviceType.Desktop][key][CurrentState.Hover]}}`;
    });

    desktopCss += desktopHoverCss += stylesMap.custom;

    Object.keys(stylesMap[DeviceType.Tablet]).forEach((key) => {
        tabletCss += `.${key}{${stylesMap[DeviceType.Tablet][key][CurrentState.Default]}}`;
        tabletHoverCss += `.${key}:hover{${stylesMap[DeviceType.Tablet][key][CurrentState.Hover]}}`;
    });

    Object.keys(stylesMap[DeviceType.Tablet]).forEach((key) => {
        mobileCss += `.${key}{${stylesMap[DeviceType.Mobile][key][CurrentState.Default]}}`;
        mobileHoverCss += `.${key}:hover{${stylesMap[DeviceType.Mobile][key][CurrentState.Hover]}}`;
    });

    const containerQueries = `@container(max-width: 768px){${tabletCss} ${tabletHoverCss}} @container(max-width: 576px){${mobileCss} ${mobileHoverCss}}`;

    // return desktopCss + ' ' + containerQueries;

    const mediaQueries = `@media(min-width: 576px)and(max-width: 768px){${tabletCss} ${tabletHoverCss}} @media (max-width: 576px){${mobileCss} ${mobileHoverCss}}`;
    return desktopCss + ' ' + containerQueries + ' ' + mediaQueries;
}
