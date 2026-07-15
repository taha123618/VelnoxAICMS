<template>
    <div class="space-y-4">
        <BuilderNumberInput
            label="Columns"
            label-position="left"
            :value="element.getProp('container.columns')"
            @change="element.setProps('container.columns', $event)"
        />

        <BuilderNumberInput
            label="Column gap"
            label-position="left"
            :value="element.getProp('container.columnGap')"
            @change="element.setProps('container.columnGap', $event)"
        />

        <BuilderNumberInput
            label="Row gap"
            label-position="left"
            :value="element.getProp('container.rowGap')"
            @change="element.setProps('container.rowGap', $event)"
        />
    </div>
</template>

<script setup lang="ts">
import BuilderNumberInput from '@modules/Builder/resources/components/form/builder-number-input.vue';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';

const { element } = defineProps<{
    element: ZioraElement;
}>();
</script>

<style scoped></style>
