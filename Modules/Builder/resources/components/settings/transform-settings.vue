<template>
    <div class="space-y-1">
        <template
            v-for="item in transformations"
            :key="item.id"
        >
            <BuilderInputGroup
                label-position="left"
                select-class="w-auto"
                :show-icons="false"
                :max="item.max"
                :min="item.min"
                v-if="element.hasStyle(item.id)"
                :options="item.options"
                :label="item.name"
                :has-changed="hasChanged(`${item.id}.value`) || hasChanged(`${item.id}.unit`)"
                @clear:hover="deleteHoverStyle(item.id)"
                @change:input="setStyle(`${item.id}.value`, $event)"
                @change:select="setStyle(`${item.id}.unit`, $event)"
                :input-value="(getStyle(`${item.id}.value`) as string | number)"
                :select-value="String(getStyle(`${item.id}.unit`))"
            />
        </template>
    </div>
</template>

<script setup lang="ts">
import BuilderInputGroup from '@modules/Builder/resources/components/form/builder-input-group.vue';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import { useElement } from '@modules/Builder/resources/scripts/use-element';

const { element } = defineProps<{
    element: ZioraElement;
}>();

const { getStyle, deleteHoverStyle, hasChanged, setStyle } =
    useElement(element);

const scaleUnitOptions = ["%", "custom"];
const rotateUnitOptions = ["deg", "custom"];

const transformations = [
    { id: "rotate", name: "Rotate", max: 180, min: -180, options: rotateUnitOptions },
    { id: "scale", name: "Scale", max: 120, min: 50, options: scaleUnitOptions }
];
</script>

<style scoped></style>
