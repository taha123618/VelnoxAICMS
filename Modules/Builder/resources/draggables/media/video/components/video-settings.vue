<template>
    <div class="space-y-3">
        <BuilderInput
            :value="element.getProp('videoId')"
            @change="element.setProps('videoId', $event)"
            placeholder="dQw4w9WgXcQ"
            label="YouTube Video ID"
        />

        <USwitch
            size="sm"
            @update:modelValue="element.setProps('mute', $event)"
            :model-value="element.getProp('mute')"
            label="Mute player"
        />

        <USwitch
            size="sm"
            @update:modelValue="element.setProps('loop', $event)"
            :model-value="element.getProp('loop')"
            label="Loop playback"
        />

        <USwitch
            size="sm"
            @update:modelValue="element.setProps('controls', $event)"
            :model-value="element.getProp('controls')"
            label="Show controls"
        />

        <USwitch
            size="sm"
            @update:modelValue="element.setProps('autoplay', $event)"
            :model-value="element.getProp('autoplay')"
            label="Autoplay"
        />

        <USwitch
            size="sm"
            @update:modelValue="element.setProps('fullscreen', $event)"
            :model-value="element.getProp('fullscreen')"
            label="Allow fullscreen"
        />

        <USwitch
            size="sm"
            @update:modelValue="element.setProps('relatedVideos', $event)"
            :model-value="element.getProp('relatedVideos')"
            label="Show related videos"
        />

    </div>
</template>

<script setup lang="ts">
import BuilderInput from '@modules/Builder/resources/components/form/builder-input.vue';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';

const { element } = defineProps<{
    element: VelnoxAIElement;
}>();



</script>

<style scoped></style>