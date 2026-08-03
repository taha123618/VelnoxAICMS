<template>
    <UContextMenu
        :disabled="disabled"
        :items="items"
        :ui="{
            content: 'w-48',
        }"
    >
        <slot />
    </UContextMenu>
</template>

<script setup lang="ts">

import { useVelnoxAI } from '@modules/Builder/resources/scripts/use-VelnoxAI';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';
import type { ContextMenuItem } from '@nuxt/ui';
import { computed } from 'vue';

const { element, disabled = false } = defineProps<{
    element: VelnoxAIElement;
    disabled?: boolean;
}>();

const store = useVelnoxAI();

const disablePaste = computed<boolean>(() => {
    return !store.cutOrCopiedElement || store.cutOrCopiedElement?.id == element.id || !element.canDrop;
});

const toast = useToast();

async function onCopy() {
    try {
        await navigator.clipboard.writeText(JSON.stringify(element));
        toast.add({
            color: "success",
            title: "JSON copied to clipboard",
        });
    } catch (error) {
        console.log(error)
        toast.add({
            color: "error",
            title: "Unable to copy to clipboard.",
        });
    }
}

const items = computed<ContextMenuItem[][]>(() => [
    [
        {
            label: 'Select',
            icon: 'ph:selection',
            disabled: disabled,
            onSelect: () => store.setSelectedElement(element),
        },
        {
            label: 'Copy JSON',
            icon: 'ph:code',
            disabled: disabled,
            onSelect: () => onCopy(),
        },
        {
            label: 'Duplicate',
            icon: 'ph:copy',
            kbds: ['meta', 'D'],
            disabled: element.isRootElement() || disabled,
            onSelect: () => store.duplicateElement(element),
        },
        {
            label: 'Copy',
            icon: 'ph:copy-simple',
            kbds: ['meta', 'C'],
            disabled: element.isRootElement() || disabled,
            onSelect: () => store.cutOrCopyElement(element, 'copy'),
        },
        {
            label: 'Cut',
            icon: 'ph:scissors',
            kbds: ['meta', 'X'],
            disabled: element.isRootElement() || disabled,
            onSelect: () => store.cutOrCopyElement(element, 'cut'),
        },
        {
            label: 'Paste',
            icon: 'tdesign:paste-filled',
            kbds: ['meta', 'V'],
            disabled: disablePaste.value || disabled,
            onSelect: () => store.pasteElement(element),
        },
        {
            label: 'Delete',
            icon: 'ph:trash',
            color: 'error',
            kbds: ['meta', 'shift', 'D'],
            disabled: element.isRootElement() || disabled,
            onSelect: () => store.deleteElement(element.id),
        },
    ],
]);
</script>

<style scoped></style>
