<template>
    <div class="space-y-2">
        <BuilderSelect
            label="Input type"
            label-position="left"
            option-is-object
            :options="formInputTypes"
            @change="element.setProps('type', $event)"
            :value="element.getProp('type')"
        />

        <BuilderInput
            label-position="left"
            :value="element.getProp('name')"
            @change="element.setProps('name', $event)"
            label="Input name"
        />

        <BuilderInput
            label-position="left"
            :value="element.getProp('label.label')"
            @change="element.setProps('label.label', $event)"
            label="Label"
        />

        <template v-if="element.getProp('type') == InputTypes.Number">
            <BuilderNumberInput
                label-position="left"
                label="Min. Value"
                :max="10000"
                :min="-10000"
                :value="element.getProp('numberMin')"
                @change="element.setProps('numberMin', $event)"
            />

            <BuilderNumberInput
                label-position="left"
                label="Max. Value"
                :max="10000"
                :min="-10000"
                :value="element.getProp('numberMax')"
                @change="element.setProps('numberMax', $event)"
            />

            <BuilderNumberInput
                label-position="left"
                label="Step"
                is-decimal
                :value="element.getProp('numberStep')"
                @change="element.setProps('numberStep', $event)"
            />
        </template>

        <template v-if="![InputTypes.Checkbox, InputTypes.Radio].includes(element.getProp('type'))">
            <BuilderInput
                label-position="left"
                :value="element.getProp('placeholder')"
                @change="element.setProps('placeholder', $event)"
                label="Placeholder"
            />
        </template>

        <template v-if="[InputTypes.Radio, InputTypes.CheckboxGroup].includes(element.getProp('type'))">
            <BuilderSelect
                label="Orientation"
                label-position="left"
                :options="['vertical', 'horizontal']"
                @change="element.setProps('orientation', $event)"
                :value="element.getProp('orientation')"
            />

            <BuilderSelect
                label="Variant"
                label-position="left"
                :options="['list', 'table', 'card']"
                @change="element.setProps('radioOrCheckboxGroupVariant', $event)"
                :value="element.getProp('radioOrCheckboxGroupVariant')"
            />
        </template>

        <template v-if="element.getProp('type') == InputTypes.Checkbox">
            <BuilderSelect
                label="Variant"
                label-position="left"
                :options="['list', 'card']"
                @change="element.setProps('checkboxVariant', $event)"
                :value="element.getProp('checkboxVariant')"
            />
        </template>

        <template
            v-if="[InputTypes.Radio, InputTypes.MultiSelect, InputTypes.Select].includes(element.getProp('type'))">
            <BuilderTextarea
                label-position="top"
                help="Enter each option in a separate line. To differentiate between label and value, separate them with a pipe char ('|''). Eg: First Name|f_name"
                :value="element.getProp('options')"
                @change="element.setProps('options', $event)"
                label="Options"
            />
        </template>

    </div>
</template>

<script setup lang="ts">
import BuilderInput from '@modules/Builder/resources/components/form/builder-input.vue';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import BuilderNumberInput from '@modules/Builder/resources/components/form/builder-number-input.vue';
import { formInputTypes } from '@modules/Builder/resources/scripts/constants';
import BuilderSelect from '@modules/Builder/resources/components/form/builder-select.vue';
import { InputTypes } from '@modules/Builder/resources/scripts/enums';
import BuilderTextarea from '@modules/Builder/resources/components/form/builder-textarea.vue';

const { element } = defineProps<{ element: ZioraElement }>();

</script>

<style scoped></style>
