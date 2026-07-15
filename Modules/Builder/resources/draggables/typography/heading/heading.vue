<template>
    <BaseElementWrapper :element="element">
        <component
            ref="editorRef"
            :is="element.props.tag"
            :contenteditable="isSelected"
            class="outline-none"
            @input="update"
            @keyup="update"
            @blur="handleBlur"
            @paste.prevent="handlePaste">
            {{ content }}
        </component>
    </BaseElementWrapper>
</template>

<script setup lang="ts">
import BaseElementWrapper from '@modules/Builder/resources/components/base-element-wrapper.vue';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import { useElement } from '@modules/Builder/resources/scripts/use-element';
import { useZiora } from '@modules/Builder/resources/scripts/use-ziora';

const { element } = defineProps<{
    element: ZioraElement;
}>();

const editorRef = ref();
const store = useZiora();

const content = computed<string>(() => {
    return (element.getContent('innerText') || '') as string;
});

const { isSelected } = useElement(element);

function replaceAll(str: string, search: string, replacement: string) {
    return str.split(search).join(replacement);
}

function currentContent() {
    return editorRef.value!.innerText;
}

function updateContent(newcontent: string) {
    editorRef.value!.innerText = newcontent;
}

function handleBlur() {
    store.clearSelectedElement();
    update();
}
function update() {
    element.setContent('innerText', currentContent());
}

watch(content, (newval) => {
    if (newval != currentContent()) {
        updateContent(newval ?? '');
    }
});

function handlePaste(event: ClipboardEvent) {
    let text = event.clipboardData?.getData('text/plain');
    if (!text) return;
    text = replaceAll(text, '\r\n', ' ');
    text = replaceAll(text, '\n', ' ');
    text = replaceAll(text, '\r', ' ');

    const selection = window.getSelection();
    if (selection && selection.rangeCount > 0) {
        const range = selection.getRangeAt(0);
        range.deleteContents();

        const textNode = document.createTextNode(text);
        range.insertNode(textNode);

        // Move cursor to end of inserted text
        range.setStartAfter(textNode);
        range.setEndAfter(textNode);
        selection.removeAllRanges();
        selection.addRange(range);
    }
}

onMounted(async () => {
    updateContent(content.value);
})
</script>

<style scoped></style>
