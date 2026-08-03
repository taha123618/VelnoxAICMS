<template>
    <div class="space-y-4">
        <div class="border border-neutral-700 space-y-2 p-3 rounded-lg">
            <h2>Mobile toggle button</h2>
            <BuilderInput
                label-position="left"
                label="Toggle text"
                input-class="w-full"
                @change="element.setProps('toggleButton.label', $event)"
                :value="element.getProp('toggleButton.label')"
            />

            <BuilderInput
                label-position="left"
                label="Toggle icon"
                input-class="w-full"
                @change="element.setProps('toggleButton.icon', $event)"
                :value="element.getProp('toggleButton.icon')"
            />

            <BuilderColorInput
                label-position="left"
                label="Button color"
                input-class="w-full"
                @change="element.setProps('toggleButton.backgroundColor', $event)"
                :value="element.getProp('toggleButton.backgroundColor')"
            />

            <BuilderSlider
                label="Button font size"
                unit="px"
                :value="element.getProp('toggleButton.fontSize')"
                @change="element.setProps('toggleButton.fontSize', $event)"
            />

            <BuilderColorInput
                label-position="left"
                label="Text color"
                input-class="w-full"
                @change="element.setProps('toggleButton.color', $event)"
                :value="element.getProp('toggleButton.color')"
            />

            <BuilderSlider
                label="Padding X"
                unit="px"
                :value="element.getProp('toggleButton.paddingX')"
                @change="element.setProps('toggleButton.paddingX', $event)"
            />

            <BuilderSlider
                label="Padding Y"
                unit="px"
                :value="element.getProp('toggleButton.paddingY')"
                @change="element.setProps('toggleButton.paddingY', $event)"
            />

            <BuilderSlider
                label="Border radius"
                unit="px"
                :value="element.getProp('toggleButton.borderRadius')"
                @change="element.setProps('toggleButton.borderRadius', $event)"
            />

            <BuilderSlider
                label="Height"
                unit="px"
                :value="element.getProp('toggleButton.height')"
                @change="element.setProps('toggleButton.height', $event)"
            />
        </div>

        <UFormField label="Slideover side">
            <URadioGroup
                orientation="horizontal"
                :items="slideOverSideOptions"
                @update:model-value="element.setProps('mobileMenu.side', $event)"
                :model-value="element.getProp('mobileMenu.side')"
            />
        </UFormField>

        <BuilderColorInput
            label-position="left"
            label="Background color"
            input-class="w-full"
            @change="element.setProps('mobileMenu.backgroundColor', $event)"
            :value="element.getProp('mobileMenu.backgroundColor')"
        />

        <BuilderColorInput
            label-position="left"
            label="Link color"
            input-class="w-full"
            @change="element.setProps('mobileMenu.color', $event)"
            :value="element.getProp('mobileMenu.color')"
        />

        <BuilderSlider
            label="Font size (px)"
            unit="px"
            :value="Number(element.getProp('mobileMenu.fontSize'))"
            @change="element.setProps('mobileMenu.fontSize', $event)"
        />

        <BuilderInput
            label-position="left"
            label="Title"
            input-class="w-full"
            @change="element.setProps('mobileMenu.menuTitle', $event)"
            :value="element.getProp('mobileMenu.menuTitle')"
        />

        <BuilderSelect
            label-position="left"
            label="Close color"
            :options="closeButtonColors"
            @change="element.setProps('mobileMenu.closeButtonColor', $event)"
            :value="element.getProp('mobileMenu.closeButtonColor')"
        />

        <BuilderSelect
            label-position="left"
            label="Close size"
            :options="closeButtonSizes"
            @change="element.setProps('mobileMenu.closeButtonSize', $event)"
            :value="element.getProp('mobileMenu.closeButtonSize')"
        />

        <BuilderSelect
            label-position="left"
            label="Close variant"
            :options="closeButtonVariants"
            @change="element.setProps('mobileMenu.closeButtonVariant', $event)"
            :value="element.getProp('mobileMenu.closeButtonVariant')"
        />
    </div>
</template>

<script setup lang="ts">
import BuilderSelect from '@modules/Builder/resources/components/form/builder-select.vue';
import BuilderColorInput from '@modules/Builder/resources/components/form/builder-color-input.vue';
import BuilderSlider from '@modules/Builder/resources/components/form/builder-slider.vue';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';
import BuilderInput from '@modules/Builder/resources/components/form/builder-input.vue';
const { element } = defineProps<{ element: VelnoxAIElement }>();

const slideOverSideOptions = ['left', 'right'];
const closeButtonSizes = ['xs', 'sm', 'md', 'lg', 'xl'];
const closeButtonVariants = ['link', 'solid', 'outline', 'soft', 'subtle', 'ghost'];
const closeButtonColors = ['error', 'primary', 'secondary', 'success', 'info', 'warning', 'neutral'];

</script>

<style scoped></style>
