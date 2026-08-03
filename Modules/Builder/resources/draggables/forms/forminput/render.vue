<template>
    <div
        :class="cn(className, customClassNames, animationClass)"
        class="builder-element z-0 box-border outline-2 outline-offset-0 outline-transparent"
    >
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
            v-model="model"
        />
        <UFormField
            v-else
            :label="element.getProp('label.label')"
            :error="error"
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
                v-bind="element.getProp('config')"
                :placeholder="element.getProp('placeholder')"
                v-model="model"
                class="w-full"
            />

            <UInput
                type="email"
                :ui="{
                    base: 'focus-visible:ring-0 p-0 form-input'
                }"
                variant="none"
                v-else-if="element.getProp('type') == InputTypes.Email"
                v-bind="element.getProp('config')"
                :placeholder="element.getProp('placeholder')"
                v-model="model"
                class="w-full"
            />

            <UInput
                type="password"
                :ui="{
                    base: 'focus-visible:ring-0 p-0 form-input'
                }"
                variant="none"
                v-else-if="element.getProp('type') == InputTypes.Password"
                v-bind="element.getProp('config')"
                :placeholder="element.getProp('placeholder')"
                v-model="model"
                class="w-full"
            />

            <UInput
                type="date"
                :ui="{
                    base: 'focus-visible:ring-0 p-0 form-input'
                }"
                variant="none"
                v-else-if="element.getProp('type') == InputTypes.Date"
                v-bind="element.getProp('config')"
                :placeholder="element.getProp('placeholder')"
                v-model="model"
                class="w-full"
            />

            <UTextarea
                :ui="{
                    base: 'focus-visible:ring-0 p-0 form-input'
                }"
                variant="none"
                v-else-if="element.getProp('type') == InputTypes.Textarea"
                v-bind="element.getProp('config')"
                :placeholder="element.getProp('placeholder')"
                class="w-full"
                v-model="model"
            />

            <UInputNumber
                color="neutral"
                :ui="{
                    base: 'focus-visible:ring-0 p-0 form-input',
                }"
                variant="none"
                v-else-if="element.getProp('type') == InputTypes.Number"
                v-bind="element.getProp('config')"
                :placeholder="element.getProp('placeholder')"
                :min="element.getProp('numberMin')"
                :max="element.getProp('numberMax')"
                :step="element.getProp('numberStep')"
                class="w-full"
                v-model="model"
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
                v-bind="element.getProp('config')"
                :items="options"
                class="w-full"
                v-model="model"
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
                v-bind="element.getProp('config')"
                :items="options"
                class="w-full"
                v-model="model"
            />


            <USelect
                :ui="{
                    base: 'ring-0 focus-visible:ring-0 p-0 select-input form-inputx'
                }"
                v-else-if="element.getProp('type') == InputTypes.Select"
                :multiple="element.getProp('selectMultiple')"
                v-bind="element.getProp('config')"
                :items="options"
                class="w-full"
                v-model="model"
            />

            <USelect
                v-else-if="element.getProp('type') == InputTypes.MultiSelect"
                multiple
                v-bind="element.getProp('config')"
                :items="options"
                class="w-full"
                v-model="model"
                :ui="{
                    base: 'ring-0 focus-visible:ring-0 p-0 select-input form-inputx'
                }"
            />

            <div v-else>Invalid element type</div>

        </UFormField>
    </div>
</template>

<script setup lang="ts">
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';
import { cn } from '@modules/Builder/resources/scripts/utils';
import { useElement } from '@modules/Builder/resources/scripts/use-element';
import { InputTypes } from '@modules/Builder/resources/scripts/enums';
import { useFormInput } from '@modules/Builder/resources/draggables/forms/forminput/use-form-input';

const { element } = defineProps<{
    element: VelnoxAIElement;
    error?: string;
}>();

const model = defineModel<any>()
const { customClassNames, animationClass, className } = useElement(element);

const { options } = useFormInput(element)

</script>

<style scoped></style>
