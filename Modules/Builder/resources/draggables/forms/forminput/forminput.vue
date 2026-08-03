<template>
    <BaseElementWrapper :element="element">
        <UCheckbox
            :ui="{
                root: `border-transparent ${element.getProp('checkboxVariant') == 'card' && 'checkbox-input-card'}`,
                indicator: 'checkbox-input-indicator',
                base: 'ring-0 checkbox-input',
                label: 'input-label'
            }"
            :variant="element.getProp('checkboxVariant')"
            v-if="element.getProp('type') == InputTypes.Checkbox"
            :label="element.getProp('label.label')"
        />
        <UFormField
            v-else
            :label="element.getProp('label.label')"
            :ui="{
                label: 'input-label'
            }"
        >
            <UInput
                type="text"
                :ui="{
                    base: 'focus-visible:ring-0 p-0 form-input'
                }"
                variant="none"
                v-if="element.getProp('type') == InputTypes.Text"
                :placeholder="element.getProp('placeholder')"
                class="w-full"
            />

            <UInput
                type="email"
                :ui="{
                    base: 'focus-visible:ring-0 p-0 form-input'
                }"
                variant="none"
                v-else-if="element.getProp('type') == InputTypes.Email"
                :placeholder="element.getProp('placeholder')"
                class="w-full"
            />

            <UInput
                type="password"
                :ui="{
                    base: 'focus-visible:ring-0 p-0 form-input'
                }"
                variant="none"
                v-else-if="element.getProp('type') == InputTypes.Password"
                :placeholder="element.getProp('placeholder')"
                class="w-full"
            />

            <UInput
                type="date"
                :ui="{
                    base: 'focus-visible:ring-0 p-0 form-input'
                }"
                variant="none"
                v-else-if="element.getProp('type') == InputTypes.Date"
                :placeholder="element.getProp('placeholder')"
                class="w-full"
            />

            <UInputNumber
                :ui="{
                    base: 'focus-visible:ring-0 p-0 form-input'
                }"
                variant="none"
                v-else-if="element.getProp('type') == InputTypes.Number"
                :placeholder="element.getProp('placeholder')"
                :min="element.getProp('numberMin')"
                :max="element.getProp('numberMax')"
                :step="element.getProp('numberStep')"
                class="w-full"
            />

            <UTextarea
                :ui="{
                    base: 'focus-visible:ring-0 p-0 form-input'
                }"
                variant="none"
                v-else-if="element.getProp('type') == InputTypes.Textarea"
                :placeholder="element.getProp('placeholder')"
                class="w-full"
            />

            <URadioGroup
                :ui="{
                    indicator: 'checkbox-input-indicator',
                    item: `border-transparent ${element.getProp('radioOrCheckboxGroupVariant') != 'list' && 'checkbox-input-card'}`,
                    fieldset: 'space-x-px',
                    base: 'radio-input',
                }"
                v-else-if="element.getProp('type') == InputTypes.Radio"
                :variant="element.getProp('radioOrCheckboxGroupVariant')"
                :orientation="element.getProp('orientation')"
                :items="options"
                class="w-full"
            />

            <UCheckboxGroup
                :ui="{
                    indicator: 'checkbox-input-indicator',
                    item: `border-transparent ${element.getProp('radioOrCheckboxGroupVariant') != 'list' && 'checkbox-input-card'}`,
                    fieldset: 'space-x-px',
                    base: 'ring-0 checkbox-input',
                }"
                v-else-if="element.getProp('type') == InputTypes.CheckboxGroup"
                :variant="element.getProp('radioOrCheckboxGroupVariant')"
                :orientation="element.getProp('orientation')"
                :items="options"
                class="w-full"
            />


            <USelect
                :ui="{
                    base: 'ring-0 focus-visible:ring-0 p-0 select-input form-inputx'
                }"
                v-else-if="element.getProp('type') == InputTypes.Select"
                :multiple="element.getProp('selectMultiple')"
                :items="options"
                class="w-full"
            />

            <USelect
                :ui="{
                    base: 'ring-0 focus-visible:ring-0 p-0 select-input form-inputx'
                }"
                v-else-if="element.getProp('type') == InputTypes.MultiSelect"
                multiple
                :items="options"
                class="w-full"
            />

            <div v-else>Invalid element type</div>
        </UFormField>
    </BaseElementWrapper>
</template>

<script setup lang="ts">
import BaseElementWrapper from '@modules/Builder/resources/components/base-element-wrapper.vue';
import { useFormInput } from '@modules/Builder/resources/draggables/forms/forminput/use-form-input';
import { InputTypes } from '@modules/Builder/resources/scripts/enums';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';

const { element } = defineProps<{
    element: VelnoxAIElement;
}>();

const { options } = useFormInput(element)


</script>

<style scoped></style>
