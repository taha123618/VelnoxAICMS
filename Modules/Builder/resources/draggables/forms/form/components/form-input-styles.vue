<template>
    <div class="space-y-2">
        <BuilderSlider
            label="Padding X"
            unit="px"
            :value="element.getProp('input.paddingX')"
            @change="element.setProps('input.paddingX', $event)"
        />

        <BuilderSlider
            label="Padding Y"
            unit="px"
            :value="element.getProp('input.paddingY')"
            @change="element.setProps('input.paddingY', $event)"
        />

        <BuilderSlider
            label="Font size"
            unit="px"
            :value="element.getProp('input.fontSize')"
            @change="element.setProps('input.fontSize', $event)"
        />

        <BuilderSlider
            label="Border width"
            unit="px"
            :value="element.getProp('input.borderWidth')"
            @change="element.setProps('input.borderWidth', $event)"
        />

        <BuilderSlider
            label="Focus border width"
            unit="px"
            :value="element.getProp('input.focusBorderWidth')"
            @change="element.setProps('input.focusBorderWidth', $event)"
        />

        <BuilderColorInput
            label-position="left"
            label="Border color"
            input-class="w-full"
            @change="element.setProps('input.borderColor', $event)"
            :value="element.getProp('input.borderColor')"
        />

        <BuilderColorInput
            label-position="left"
            label="Focus border color"
            input-class="w-full"
            @change="element.setProps('input.focusBorderColor', $event)"
            :value="element.getProp('input.focusBorderColor')"
        />

        <template
            v-if="element.getProp('type') == InputTypes.Checkbox || element.getProp('type') == InputTypes.Radio || element.getProp('type') == InputTypes.CheckboxGroup"
        >
            <BuilderSlider
                label="Indicator width"
                unit="px"
                :value="element.getProp('checkbox.width')"
                @change="element.setProps('checkbox.width', $event)"
            />

            <BuilderSlider
                label="Indicator height"
                unit="px"
                :value="element.getProp('checkbox.height')"
                @change="element.setProps('checkbox.height', $event)"
            />
        </template>
    </div>
</template>

<script setup lang="ts">

import BuilderSlider from '@modules/Builder/resources/components/form/builder-slider.vue';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import BuilderColorInput from '@modules/Builder/resources/components/form/builder-color-input.vue';
import { InputTypes } from '@modules/Builder/resources/scripts/enums';

const { element } = defineProps<{ element: ZioraElement }>();

</script>

<style scoped></style>
