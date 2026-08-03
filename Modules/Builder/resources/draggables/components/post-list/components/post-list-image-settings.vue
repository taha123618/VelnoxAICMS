<template>
    <div class="space-y-4">
        <USwitch
            size="sm"
            @update:modelValue="element.setProps('img.show', $event)"
            :model-value="element.getProp('img.show')"
            label="Show image" />
        <template
            v-for="item in imgDimensions"
            :key="item.id">
            <BuilderInputGroup
                label-position="left"
                select-class="w-auto"
                :show-icons="false"
                :max="10000"
                :disabled="element.getProp(`${item.id}.unit`) == 'auto'"
                :options="item.options"
                :label="item.name"
                @change:input="element.setProps(`${item.id}.value`, $event)"
                @change:select="element.setProps(`${item.id}.unit`, $event)"
                :input-value="element.getProp(`${item.id}.value`)"
                :select-value="element.getProp(`${item.id}.unit`)" />
        </template>
    </div>
</template>

<script setup lang="ts">
import BuilderInputGroup from '@modules/Builder/resources/components/form/builder-input-group.vue';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';

const { element } = defineProps<{
    element: VelnoxAIElement;
}>();

const heightUnitOptions = ['auto', '%', 'px', 'rem', 'vh', 'em'];
const widthUnitOptions = ['auto', '%', 'px', 'rem', 'vw', 'em'];

const imgDimensions = [
    { id: 'img.height', name: 'Image height', options: heightUnitOptions },
    { id: 'img.width', name: 'Image Width', options: widthUnitOptions },
];
</script>

<style scoped></style>
