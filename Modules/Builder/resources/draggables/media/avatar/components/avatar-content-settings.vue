<template>
    <div class="space-y-4">
        <BuilderImagePicker
            label="Choose image"
            :value="element.getProp('src')"
            @remove="element.setProps('src', '')"
            @change="element.setProps('src', $event)"
        />
        <BuilderInput
            label="Text"
            placeholder="eg JD"
            :value="element.getProp('text')"
            @change="element.setProps('text', $event)"
        />
        <BuilderInput
            label="Alt"
            placeholder="eg John Doe"
            :value="element.getProp('alt')"
            @change="element.setProps('alt', $event)"
        />
    </div>
</template>

<script setup lang="ts">
import BuilderImagePicker from '@modules/Builder/resources/components/form/builder-image-picker.vue';
import BuilderInput from '@modules/Builder/resources/components/form/builder-input.vue';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';

const { element } = defineProps<{
    element: VelnoxAIElement;
}>();

</script>

<style scoped></style>
