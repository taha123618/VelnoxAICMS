<template>
    <div class="space-y-4">
        <USwitch
            size="sm"
            @update:modelValue="element.setProps('logo.display', $event)"
            :model-value="element.getProp('logo.display')"
            label="Show logo"
        />

        <BuilderImagePicker
            label="Choose logo"
            :value="element.getProp('logo.src')"
            @remove="element.setProps('logo.src', '')"
            @change="element.setProps('logo.src', $event)"
        />

        <BuilderInput
            label="or paste link"
            @change="element.setProps('logo.src', $event)"
            placeholder="https://placehold.co/40x40/orange/white"
            :value="element.getProp('logo.src')"
        />
        <template
            v-for="item in dimensions"
            :key="item.id"
        >
            <BuilderInputGroup
                label-position="left"
                select-class="w-auto"
                :show-icons="false"
                :max="10000"
                :disabled="element.getProp(`logo.${item.id}.unit`) == 'auto'"
                :options="item.options"
                :label="item.name"
                @change:input="element.setProps(`logo.${item.id}.value`, $event)"
                @change:select="element.setProps(`logo.${item.id}.unit`, $event)"
                :input-value="element.getProp(`logo.${item.id}.value`)"
                :select-value="String(element.getProp(`logo.${item.id}.unit`))"
            />
        </template>
    </div>
</template>

<script setup lang="ts">
import BuilderImagePicker from '@modules/Builder/resources/components/form/builder-image-picker.vue';
import BuilderInputGroup from '@modules/Builder/resources/components/form/builder-input-group.vue';
import BuilderInput from '@modules/Builder/resources/components/form/builder-input.vue';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';
const { element } = defineProps<{ element: VelnoxAIElement }>();

const widthUnitOptions = ['auto', '%', 'px', 'rem', 'vw', 'em'];
const heightUnitOptions = ['auto', '%', 'px', 'rem', 'vh', 'em'];
const dimensions = [
    { id: 'height', name: 'Height', options: heightUnitOptions },
    { id: 'width', name: 'Width', options: widthUnitOptions },
];
</script>

<style scoped></style>
