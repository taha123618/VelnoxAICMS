<template>
    <div class="space-y-4">
        <BuilderTiptap
            :font-size="`${getStyle('fontSize.value')}${getStyle('fontSize.unit')}`"
            :value="element.getContent('innerText')"
            @change="element.setContent('innerText', $event)"
        />
        <VisibilitySettings :options="['block', 'inline', 'inline-block', 'none']" :element="element" />
    </div>
</template>

<script setup lang="ts">
import BuilderTiptap from '@modules/Builder/resources/components/form/editor/builder-tiptap.vue';
import VisibilitySettings from '@modules/Builder/resources/components/settings/visibility-settings.vue';
import { useElement } from '@modules/Builder/resources/scripts/use-element';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';

const { element } = defineProps<{
    element: VelnoxAIElement;
}>();

const { getStyle } = useElement(element);
</script>

<style scoped></style>
