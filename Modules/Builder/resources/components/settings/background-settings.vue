<template>
    <div class="space-y-2">
        <BuilderToggleGroup
            label-position="left"
            :value="getStyle('background.type')"
            :has-changed="hasChanged('background.type')"
            @clear:hover="deleteHoverStyle('background.type')"
            @change="setStyle('background.type', $event)"
            option-is-object
            label="Type"
            :options="backgroundTypes"
        />

        <template v-if="getStyle('background.type') === BackgroundType.Gradient">
            <BuilderColorInput
                label-position="left"
                label="Gradient"
                :has-changed="hasChanged('background.gradientBackgroundImage')"
                @clear:hover="deleteHoverStyle('background.gradientBackgroundImage')"
                input-class="w-full"
                property-type="gradient"
                @change="setStyle('background.gradientBackgroundImage', $event)"
                :value="String(getStyle('background.gradientBackgroundImage'))"
            />
        </template>

        <!-- CLASSIC COLOR AND IMAGE -->
        <template v-if="getStyle('background.type') === BackgroundType.Classic">
            <BuilderColorInput
                label-position="left"
                label="Color"
                :has-changed="hasChanged('background.color')"
                @clear:hover="deleteHoverStyle('background.color')"
                input-class="w-full"
                @change="setStyle('background.color', $event)"
                :value="String(getStyle('background.color'))"
            />
        </template>

        <BuilderImagePicker
            label="Choose background image"
            :value="(getStyle('background.imageSource') as string)"
            :has-changed="hasChanged('background.imageSource')"
            @clear:hover="deleteHoverStyle('background.imageSource')"
            @remove="setStyle('background.imageSource', '')"
            @change="setStyle('background.imageSource', $event)"
        />

        <BuilderInput
            label="or paste link"
            :has-changed="hasChanged('background.imageSource')"
            @clear:hover="deleteHoverStyle('background.imageSource')"
            @change="setStyle('background.imageSource', $event)"
            placeholder="https://placehold.co/600x400/orange/white"
            :value="String(getStyle('background.imageSource') || '')"
        />

        <template v-if="getStyle('background.imageSource')">
            <BuilderSelect
                label="Position"
                label-position="left"
                :options="backgroundPositions"
                :has-changed="hasChanged('background.imagePosition')"
                @clear:hover="deleteHoverStyle('background.imagePosition')"
                @change="setStyle('background.imagePosition', $event)"
                :value="String(getStyle('background.imagePosition'))"
            />
            <BuilderSelect
                label="Attachment"
                label-position="left"
                :options="imageAttachmentTypes"
                :has-changed="hasChanged('background.imageAttachment')"
                @clear:hover="deleteHoverStyle('background.imageAttachment')"
                @change="setStyle('background.imageAttachment', $event)"
                :value="String(getStyle('background.imageAttachment'))"
            />
            <BuilderSelect
                label="Repeat"
                label-position="left"
                :options="imageRepeatTypes"
                :has-changed="hasChanged('background.imageRepeat')"
                @clear:hover="deleteHoverStyle('background.imageRepeat')"
                @change="setStyle('background.imageRepeat', $event)"
                :value="String(getStyle('background.imageRepeat'))"
            />
            <BuilderSelect
                label="Size"
                label-position="left"
                :options="imageSizes"
                :has-changed="hasChanged('background.imageSize')"
                @clear:hover="deleteHoverStyle('background.imageSize')"
                @change="setStyle('background.imageSize', $event)"
                :value="String(getStyle('background.imageSize'))"
            />
        </template>
    </div>
</template>

<script setup lang="ts">
import { BackgroundType } from '@modules/Builder/resources/scripts/enums';
import { useElement } from '@modules/Builder/resources/scripts/use-element';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import BuilderImagePicker from '@modules/Builder/resources/components/form/builder-image-picker.vue';
import BuilderColorInput from '@modules/Builder/resources/components/form/builder-color-input.vue';
import BuilderInput from '@modules/Builder/resources/components/form/builder-input.vue';
import BuilderSelect from '@modules/Builder/resources/components/form/builder-select.vue';
import BuilderToggleGroup from '@modules/Builder/resources/components/form/builder-toggle-group.vue';

const { element } = defineProps<{ element: ZioraElement }>();

const { getStyle, hasChanged, deleteHoverStyle, setStyle } =
    useElement(element);

const backgroundTypes = [
    {
        value: BackgroundType.Classic,
        label: 'Classic',
        icon: 'ph:palette',
    },
    {
        value: BackgroundType.Gradient,
        label: 'Gradient',
        icon: 'ph:gradient',
    },
];

const imageSizes = ['auto', 'cover', 'contain'];

const backgroundPositions = [
    'center center',
    'center left',
    'center right',
    'top center',
    'top left',
    'top right',
    'bottom center',
    'bottom left',
    'bottom right',
];

const imageAttachmentTypes = ['fixed', 'scroll'];

const imageRepeatTypes = ['no-repeat', 'repeat', 'repeat-x', 'repeat-y'];

</script>

<style scoped></style>
