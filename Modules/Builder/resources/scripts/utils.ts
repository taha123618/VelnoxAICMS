import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import { type ClassValue, clsx } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export function isEmpty(value: any) {
    if (Array.isArray(value)) {
        return value.length === 0;
    }
    if (value && typeof value === 'object') {
        return Object.keys(value).length === 0;
    }
    return false;
}

export function cursorIsonEdge(
    cursorPosition: Record<string, any>,
    targetElement: HTMLElement,
    edgeThickness: number = 8,
): boolean {
    const { top, left, width, height } = targetElement.getBoundingClientRect();
    const { clientX, clientY } = cursorPosition;
    if (
        clientX < left + width - edgeThickness &&
        clientX > left + edgeThickness &&
        clientY > top + edgeThickness &&
        clientY < top + height - edgeThickness
    ) {
        return false;
    }
    return true;
}

const RESIZE_MARGIN = 4;

export const calculator = {
    width: (rect: any, direction: 'right' | 'left', clientX = 0) => {
        if (direction === 'right') {
            return clientX - rect.left + RESIZE_MARGIN;
        } else {
            return rect.right - clientX + RESIZE_MARGIN;
        }
    },
    height: (rect: any, direction: 'bottom' | 'top', clientY = 0) => {
        if (direction === 'bottom') {
            return clientY - rect.top + RESIZE_MARGIN;
        } else {
            return rect.bottom - clientY + RESIZE_MARGIN;
        }
    },
};

export function deepCopy<T>(obj: T): T {
    if (obj === null || typeof obj !== 'object') {
        return obj;
    }

    const copy: any = Array.isArray(obj) ? [] : {};

    Object.keys(obj).forEach((key) => {
        copy[key] = deepCopy((obj as any)[key]);
    });

    return copy;
}

export function parseElements(item: any) {
    return [ZioraElement.fromObject(item[0])];
}

/**
 * If the given value is not an array, wrap it in one.
 */
export function arrayWrap<T>(value: T): T[] {
    return Array.isArray(value) ? value : [value];
}

export function getBaseFolderName(filePath: string): string | null | undefined {
    const parts = filePath.split('/').filter(Boolean); // Remove empty parts
    const baseFolder = parts.length > 1 ? parts[parts.length - 2] : null;
    return baseFolder;
}

export function objectIsEqual<T>(x: T, y: T): boolean {
    const ok = Object.keys;
    const tx = typeof x,
        ty = typeof y;

    if (x && y && tx === 'object' && tx === ty) {
        return (
            ok(x).length === ok(y).length &&
            ok(x).every((key) =>
                objectIsEqual((x as any)[key], (y as any)[key]),
            )
        );
    }
    return x === y;
}

export function transformOptions(input: string) {
    return input
        .split('\n') // Split by lines
        .map((line) => line.trim()) // Trim each line
        .filter((line) => line.length > 0) // Remove empty lines
        .map((line) => {
            if (line.includes('|')) {
                const [label, value] = line
                    .split('|')
                    .map((part) => part.trim());
                return { label, value };
            } else {
                return line;
            }
        });
}

export function resolveLibraryTokens(
    data: any,
    tokens: Record<string, string>,
): any {
    const replaceToken = (value: any): any => {
        if (typeof value === 'string') {
            const match = value.match(/^{(.+)}$/);
            if (match && tokens[match[1]]) {
                return tokens[match[1]];
            }
        }
        return value;
    };

    const walk = (obj: any): any => {
        if (Array.isArray(obj)) {
            return obj.map(walk);
        } else if (typeof obj === 'object' && obj !== null) {
            return Object.fromEntries(
                Object.entries(obj).map(([k, v]) => [k, walk(v)]),
            );
        } else {
            return replaceToken(obj);
        }
    };

    return walk(data);
}
