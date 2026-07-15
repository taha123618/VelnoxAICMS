<template>
    <div class="space-y-2">
        <VisibilitySettings
            :options="['block', 'none']"
            :element="element"
        />
        <BuilderTextarea
            :value="(element.getContent('innerText') as string)"
            @change="element.setContent('innerText', $event)"
            label="Heading content"
        />

        <BuilderSelect
            option-is-object
            :options="headingTags"
            @change="element.setProps('tag', $event)"
            :value="element.getProp('tag')"
        />

    </div>
</template>

<script setup lang="ts">
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import BuilderSelect from '@modules/Builder/resources/components/form/builder-select.vue';
import BuilderTextarea from '@modules/Builder/resources/components/form/builder-textarea.vue';
import VisibilitySettings from '@modules/Builder/resources/components/settings/visibility-settings.vue';
const { element } = defineProps<{
    element: ZioraElement;
}>();

const headingTags = [
    { value: 'h1', label: 'H1' },
    { value: 'h2', label: 'H2' },
    { value: 'h3', label: 'H3' },
    { value: 'h4', label: 'H4' },
    { value: 'h5', label: 'H5' },
    { value: 'h6', label: 'H6' },
];
</script>

<style scoped></style>
