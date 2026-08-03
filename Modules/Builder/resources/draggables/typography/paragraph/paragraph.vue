<template>
    <BaseElementWrapper :element="element">
        <editor-content :editor="editorRef" spellcheck="false" />
    </BaseElementWrapper>
</template>

<script setup lang="ts">
import BaseElementWrapper from '@modules/Builder/resources/components/base-element-wrapper.vue';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';
import { Color } from '@tiptap/extension-color';
import Highlight from '@tiptap/extension-highlight';
import TextStyle from '@tiptap/extension-text-style';
import Typography from '@tiptap/extension-typography';
import Underline from '@tiptap/extension-underline';
import StarterKit from '@tiptap/starter-kit';
import Link from '@tiptap/extension-link';
import {
    Editor,
    Content,
    EditorContent,
} from '@tiptap/vue-3'
import { useTiptap } from '@modules/Builder/resources/components/form/editor/use-tiptap';
import { useVelnoxAI } from '@modules/Builder/resources/scripts/use-VelnoxAI';


const { element } = defineProps<{
    element: VelnoxAIElement;
}>();

const content = computed<string>(() => {
    return (element.getContent('innerText') || '') as string
});

const { processHtmlContent } = useTiptap()
const store = useVelnoxAI()
const editorRef = ref<Editor>();

watch(content,
    async (newValue) => {
        if (processHtmlContent(editorRef.value?.getHTML() || '') === newValue) {
            return;
        }
        editorRef.value?.commands.setContent(newValue as Content, false);
    },
);

onBeforeMount(() => {
    editorRef.value = new Editor({
        content: content.value as Content,
        editable: store.builderType != 'layout' && element.isLayoutElement ? false : true,
        extensions: [
            StarterKit.configure({
                listItem: {
                    HTMLAttributes: {
                        class: '[&>p]:inline-block',
                    },
                },
            }),
            Typography,
            Underline,
            Highlight,
            Color,
            TextStyle,
            Link.configure({
                openOnClick: false,
                defaultProtocol: 'https',
                HTMLAttributes: {
                    class: 'underline',
                },
            }),
        ],
        onUpdate: () => {
            const html = editorRef.value?.getHTML() || '';
            nextTick(() => {
                element.setContent('innerText', processHtmlContent(html))
            })
        },
    })
})

onBeforeUnmount(() => {
    editorRef.value?.destroy()
})

</script>

<style scoped></style>
