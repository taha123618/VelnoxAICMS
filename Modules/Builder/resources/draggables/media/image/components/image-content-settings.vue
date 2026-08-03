<template>
    <div class="space-y-4">
        <VisibilitySettings
            :options="['block', 'none']"
            :element="element"
        />

        <BuilderImagePicker
            label="Choose image"
            :value="String(element.getContent('src'))"
            @remove="element.setContent('src', '')"
            @change="element.setContent('src', $event)"
        />
        <BuilderInput
            :value="element.getContent('src')"
            @change="element.setContent('src', $event)"
            label="or Paste Link"
        />

        <BuilderSelect
            label="Image fit"
            label-position="left"
            option-is-object
            :options="objectFit"
            @change="element.setProps('content.objectFit', $event)"
            :value="element.getProp('content.objectFit')"
        />

        <BuilderSelect
            label="Link"
            label-position="left"
            option-is-object
            :options="linkTypes"
            @change="element.setContent('linkType', $event)"
            :value="element.getContent('linkType')"
        />

        <template v-if="element.getContent('linkType') == 'external'">
            <BuilderInput
                placeholder="https://mylink.com"
                :value="element.getContent('href')"
                @change="element.setContent('href', $event)"
                label="URL"
            />
        </template>
        <template v-else-if="element.getContent('linkType') == 'page'">
            <BuilderSelect
                label="Select page"
                :options="pages"
                label-field="title"
                value-field="url"
                @change="element.setContent('href', $event)"
                :value="element.getContent('href')"
            />
        </template>
        <template v-if="element.getContent('linkType') != 'none'">
            <BuilderSelect
                label="Link target"
                option-is-object
                :options="linkTargets"
                @change="element.setContent('target', $event)"
                :value="element.getContent('target')"
            />
        </template>
    </div>
</template>

<script setup lang="ts">
import BuilderImagePicker from '@modules/Builder/resources/components/form/builder-image-picker.vue';
import BuilderInput from '@modules/Builder/resources/components/form/builder-input.vue';
import BuilderSelect from '@modules/Builder/resources/components/form/builder-select.vue';
import VisibilitySettings from '@modules/Builder/resources/components/settings/visibility-settings.vue';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';
import { usePage } from '@inertiajs/vue3';

const pages = usePage().props.pages as Modules.Page.Data.PageData;

const { element } = defineProps<{
    element: VelnoxAIElement;
}>();

const objectFit = [
    { value: 'none', label: 'None' },
    { value: 'contain', label: 'Contain' },
    { value: 'cover', label: 'Cover' },
    { value: 'fill', label: 'Fill' },
    { value: 'scale-down', label: 'Scale down' },
];

const linkTargets = [
    { value: '_self', label: 'Self' },
    { value: '_blank', label: 'Blank' },
];

const linkTypes = [
    { value: 'none', label: 'None' },
    { value: 'page', label: 'Page' },
    { value: 'external', label: 'External url' },
];
</script>

<style scoped></style>
