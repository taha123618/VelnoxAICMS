<template>
    <UFormField
        :label="label"
        :ui="{
            label: 'block font-normal ziora-label',
            container: labelPosition == 'left' ? 'col-span-2 mt-0' : 'relative',
        }"
        :class="[
            labelPosition == 'left'
                ? 'grid grid-cols-3 gap-1 items-center'
                : 'flex flex-col gap-1',
        ]"
    >
        <template #label>
            <div class="flex items-center gap-0.5">
                <div>{{ label }}</div>
                <BaseTooltip
                    v-if="hasChanged"
                    content="Clear hover style"
                >
                    <UButton
                        @click.prevent="emit('clear:hover')"
                        size="sm"
                        color="error"
                        variant="link"
                        icon="ph:x"
                    />
                </BaseTooltip>
            </div>
        </template>
        <UButtonGroup
            size="xs"
            class="dark"
            orientation="horizontal"
        >
            <!-- <template
                v-for="option in options"
                :key="optionIsObject ? option[valueField] : option"
            > -->
                <BaseTooltip 
                    v-for="option in options"
                :key="optionIsObject ? option[valueField] : option"
                    :content="optionIsObject ? option[labelField] : option">
                    <UButton
                        :ui="{
                            leadingIcon: 'size-3',
                        }"
                        @click.prevent="
                            emit(
                                'change',
                                optionIsObject ? option[valueField] : option,
                            )
                            "
                        :color="(option[valueField] || option) == value
                                ? 'primary'
                                : 'neutral'
                            "
                        :variant="(option[valueField] || option) == value
                                ? 'solid'
                                : 'subtle'
                            "
                        :icon="option[iconField]"
                    />
                </BaseTooltip>
            <!-- </template> -->
        </UButtonGroup>
    </UFormField>
</template>

<script setup lang="ts">
import BaseTooltip from "@modules/Builder/resources/components/base-tooltip.vue";
import { TLabelPosition } from "@modules/Builder/resources/scripts/types";

interface Props {
    value: string | number | object | null;
    inputClass?: string;
    isIcon?: boolean;
    label?: string;
    nameField?: string;
    labelField?: string;
    valueField?: string;
    iconField?: string;
    placeholder?: string;
    labelPosition?: TLabelPosition;
    options?: any;
    optionIsObject?: boolean;
    hasChanged?: boolean;
}

withDefaults(defineProps<Props>(), {
    isIcon: true,
    labelPosition: 'top',
    nameField: 'name',
    labelField: 'label',
    valueField: 'value',
    iconField: 'icon',
    inputClass: '',
    optionIsObject: false,
    hasChanged: false,
    options: () => [],
});

const emit = defineEmits(['change', 'clear:hover']);
</script>

<style scoped></style>
