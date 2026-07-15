<template>
    <UFormField
        :label="label"
        :ui="{
            label: 'block font-normal ziora-label',
            container: labelPosition == 'left' ? 'col-span-2 mt-0' : 'relative',
        }"
        :class="[
            labelPosition == 'left'
                ? 'grid grid-cols-3 gap-1 items-center'
                : 'flexx flex-colx gap-1x',
        ]"
    >
        <div class="relative">
            <div
                class="mb-1"
                v-if="editorRef"
            >
                <div
                    :class="{
                        'overflow-hidden rounded-xl bg-white shadow-lg':
                            !showToolbar,
                    }"
                    :tippy-options="{ duration: 100 }"
                    :editor="editorRef"
                >
                    <UButtonGroup
                        :size="buttonProps.size"
                        orientation="horizontal"
                    >
                        <BaseTooltip content="Bold">
                            <UButton
                                icon="ph:text-b"
                                @click="
                                    editorRef.chain().focus().toggleBold().run()
                                    "
                                :disabled="!editorRef
                                    .can()
                                    .chain()
                                    .focus()
                                    .toggleBold()
                                    .run()
                                    "
                                :color="editorRef.isActive('bold')
                                    ? 'primary'
                                    : 'neutral'
                                    "
                                :variant="editorRef.isActive('bold')
                                    ? 'solid'
                                    : buttonProps.variant
                                    "
                            />
                        </BaseTooltip>
                        <BaseTooltip content="Italic">
                            <UButton
                                icon="ph:text-italic"
                                @click="
                                    editorRef.chain().focus().toggleItalic().run()
                                    "
                                :disabled="!editorRef
                                    .can()
                                    .chain()
                                    .focus()
                                    .toggleItalic()
                                    .run()
                                    "
                                :color="editorRef.isActive('italic')
                                    ? 'primary'
                                    : 'neutral'
                                    "
                                :variant="editorRef.isActive('italic')
                                    ? 'solid'
                                    : buttonProps.variant
                                    "
                            />
                        </BaseTooltip>
                        <BaseTooltip content="Underline">
                            <UButton
                                icon="ph:text-underline"
                                @click="
                                    editorRef
                                        .chain()
                                        .focus()
                                        .toggleUnderline()
                                        .run()
                                    "
                                :disabled="!editorRef
                                    .can()
                                    .chain()
                                    .focus()
                                    .toggleUnderline()
                                    .run()
                                    "
                                :color="editorRef.isActive('underline')
                                    ? 'primary'
                                    : 'neutral'
                                    "
                                :variant="editorRef.isActive('underline')
                                    ? 'solid'
                                    : buttonProps.variant
                                    "
                            />
                        </BaseTooltip>
                        <BaseTooltip content="Strike">
                            <UButton
                                icon="ph:text-strikethrough"
                                @click="
                                    editorRef.chain().focus().toggleStrike().run()
                                    "
                                :disabled="!editorRef
                                    .can()
                                    .chain()
                                    .focus()
                                    .toggleStrike()
                                    .run()
                                    "
                                :color="editorRef.isActive('strike')
                                    ? 'primary'
                                    : 'neutral'
                                    "
                                :variant="editorRef.isActive('strike')
                                    ? 'solid'
                                    : buttonProps.variant
                                    "
                            />
                        </BaseTooltip>
                        <!-- <BaseTooltip content="Bullet list">
                            <UButton
                                icon="ph:list-bullets"
                                @click="
                                    editorRef
                                        .chain()
                                        .focus()
                                        .toggleBulletList()
                                        .run()
                                    "
                                :disabled="!editorRef
                                    .can()
                                    .chain()
                                    .focus()
                                    .toggleBulletList()
                                    .run()
                                    "
                                :color="editorRef.isActive('bulletList')
                                    ? 'primary'
                                    : 'neutral'
                                    "
                                :variant="editorRef.isActive('bulletList')
                                    ? 'solid'
                                    : buttonProps.variant
                                    "
                            />
                        </BaseTooltip>
                        <BaseTooltip content="Numbered list">
                            <UButton
                                icon="ph:list-numbers"
                                @click="
                                    editorRef
                                        .chain()
                                        .focus()
                                        .toggleOrderedList()
                                        .run()
                                    "
                                :disabled="!editorRef
                                    .can()
                                    .chain()
                                    .focus()
                                    .toggleOrderedList()
                                    .run()
                                    "
                                :color="editorRef.isActive('orderedList')
                                    ? 'primary'
                                    : 'neutral'
                                    "
                                :variant="editorRef.isActive('orderedList')
                                    ? 'solid'
                                    : buttonProps.variant
                                    "
                            />
                        </BaseTooltip> -->

                        <BaseTooltip content="Insert/update link">
                            <UButton
                                :disabled="!hasSelection"
                                @click.prevent.stop="handleLinkOpenClick"
                                icon="ph:link-simple"
                                :color="editorRef.isActive('link')
                                    ? 'primary'
                                    : 'neutral'
                                    "
                                :variant="editorRef.isActive('link')
                                    ? 'solid'
                                    : buttonProps.variant
                                    "
                            />
                        </BaseTooltip>
                        <BaseTooltip content="Unlink">
                            <UButton
                                :disabled="!hasSelection || !editorRef?.getAttributes('link')?.href"
                                @click.prevent.stop="editorRef?.chain().focus().unsetLink().run()"
                                icon="ph:link-break"
                                :color="editorRef.isActive('link')
                                    ? 'warning'
                                    : 'neutral'
                                    "
                                :variant="editorRef.isActive('link')
                                    ? 'solid'
                                    : buttonProps.variant
                                    "
                            />
                        </BaseTooltip>

                        <BaseTooltip content="Color">
                            <UPopover>
                                <UButton
                                    :disabled="!hasSelection"
                                    :color="editorRef.isActive('textStyle', {
                                        color: editorRef.getAttributes(
                                            'textStyle',
                                        ).color,
                                    })
                                        ? 'primary'
                                        : 'neutral'
                                        "
                                    :variant="editorRef.isActive('textStyle', {
                                        color: editorRef.getAttributes(
                                            'textStyle',
                                        ).color,
                                    })
                                        ? buttonProps.variant
                                        : buttonProps.variant
                                        "
                                >
                                    <UIcon
                                        name="ph:drop-fill"
                                        :style="{
                                            color: editorRef.getAttributes(
                                                'textStyle',
                                            ).color,
                                        }"
                                    />
                                </UButton>
                                <template #content>
                                    <UColorPicker
                                        :model-value="editorRef.getAttributes('textStyle')
                                            .color
                                            "
                                        size="xs"
                                        format="hex"
                                        @update:modelValue="
                                            editorRef
                                                .chain()
                                                .focus()
                                                .setColor($event as string)
                                                .run()
                                            "
                                    />
                                </template>
                            </UPopover>
                        </BaseTooltip>
                    </UButtonGroup>
                </div>
            </div>
            <EditorContent
                spellcheck="false"
                autocomplete="off"
                autocorrect="off"
                autocapitalize="off"
                :class="{
                    'min-h-40 rounded-[calc(var(--ui-radius)*1.5)] bg-(--ui-bg-elevated) p-2 text-sm text-(--ui-text-white) ring ring-(--ui-border-accented) ring-inset focus-within:ring focus-within:ring-(--ui-primary) focus-within:ring-inset':
                        showToolbar,
                }"
                :editor="editorRef"
            />
        </div>
    </UFormField>
</template>

<script setup lang="ts">
import BaseTooltip from "@modules/Builder/resources/components/base-tooltip.vue";
import { useTiptap } from "@modules/Builder/resources/components/form/editor/use-tiptap";
import { TLabelPosition } from "@modules/Builder/resources/scripts/types";
import { Color } from '@tiptap/extension-color';
import Highlight from '@tiptap/extension-highlight';
import Link from '@tiptap/extension-link';
import Placeholder from '@tiptap/extension-placeholder';
import TextStyle from '@tiptap/extension-text-style';
import Typography from '@tiptap/extension-typography';
import Underline from '@tiptap/extension-underline';
import StarterKit from '@tiptap/starter-kit';
import { visitModal } from '@inertiaui/modal-vue'
import { Content, Editor, EditorContent } from '@tiptap/vue-3';

interface Props {
    fontSize: string | number;
    value: string | number | undefined;
    label?: string;
    disabled?: boolean;
    showToolbar?: boolean;
    placeholder?: string;
    labelPosition?: TLabelPosition;
}

const props = withDefaults(defineProps<Props>(), {
    labelPosition: 'top',
    disabled: false,
    showToolbar: true,
    maxRows: 6,
});

const emit = defineEmits(['change']);

const hasSelection = computed(() => {
    if (!editorRef.value) return false
    const { empty, from, to } = editorRef.value.state.selection
    return !empty && from !== to
})

const editorRef = ref<Editor>();
const isFocused = ref(false);
const buttonProps = computed(() => {
    return {
        size: (props.showToolbar ? 'sm' : 'md') as
            | 'sm'
            | 'md'
            | 'xs'
            | 'lg'
            | 'xl'
            | undefined,
        variant: (props.showToolbar ? 'subtle' : 'ghost') as
            | 'link'
            | 'subtle'
            | 'ghost'
            | 'solid'
            | 'outline'
            | 'soft'
            | undefined,
    };
});

watch(
    () => props.value,
    (newValue) => {
        if (processHtmlContent(editorRef.value?.getHTML() || '') === newValue) {
            return;
        }
        editorRef.value?.commands.setContent(newValue as Content);
    },
);

function handleLinkOpenClick() {
    visitModal(route('admin.links.picker'), {
        data: {
            linkType:editorRef.value?.getAttributes('link')?.class || 'external',
            href: editorRef.value?.getAttributes('link')?.href || null
        },
        listeners: {
            insert(payload: Record<string, any>) {
                const selectedLink = payload.href
                if (!!selectedLink) {
                    editorRef.value
                        ?.chain()
                        .focus()
                        .extendMarkRange('link')
                        .setLink({ href: selectedLink, class: payload.linkType })
                        .run();
                } else {
                    editorRef.value?.chain().focus().unsetLink().run();
                }

            }
        },
    })
}


const { processHtmlContent } = useTiptap()

onBeforeMount(() => {
    editorRef.value = new Editor({
        content: props.value as Content,
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
                    class: 'underline builder-link',
                    rel: null,
                    target: null,
                    type: 'tag'
                },
            }),
            Placeholder.configure({
                placeholder: props.placeholder,
            }),
        ],
        onFocus: () => (isFocused.value = true),
        onBlur: () => {
            isFocused.value = false;
        },
        onUpdate: () => {
            const html = editorRef.value?.getHTML() || '';
            emit('change', processHtmlContent(html));
        },
    });
});

onBeforeUnmount(() => {
    editorRef.value?.destroy();
});
</script>

<style lang="scss"></style>
