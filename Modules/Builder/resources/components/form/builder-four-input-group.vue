<template>
    <UFormField
        :label="label"
        :ui="{
            label: 'block font-normal ziora-label',
        }"
        class="flex flex-col gap-1 text-xs"
    >
        <template #label>
            <div class="flex items-center gap-0.5">
                <div>{{ label }}</div>
                <BaseTooltip
                    v-if="hasChanged"
                    content="Clear hover style"
                >
                    <UButton
                        @click="emit('clear:hover')"
                        size="sm"
                        color="error"
                        variant="link"
                        icon="ph:x"
                    />
                </BaseTooltip>
            </div>
        </template>
        <template #hint>
            <USelect
                v-if="inputValue.hasUnit"
                :model-value="inputValue.unit"
                :items="unitOptions"
                size="xs"
                arrow
                variant="subtle"
                class="dark"
                :content="{
                    align: 'center',
                    side: 'bottom',
                    sideOffset: 0,
                }"
                :ui="{
                    content: 'dark min-w-16',
                }"
                :value-key="unitOptionsValueField"
                :label-key="unitOptionsLabelField"
                @update:model-value="handleChange('unit', $event)"
            />
        </template>

        <div class="bg-primary/5 relative mb-3 rounded-lg p-6">
            <template
                v-for="position in positions"
                :key="position"
            >
                <div
                    :class="{
                        'top-2 left-1/2 -translate-x-1/2': position == 'top',
                        'top-1/2 right-2 -translate-y-1/2': position == 'right',
                        'bottom-2 left-1/2 -translate-x-1/2':
                            position == 'bottom',
                        'top-1/2 left-2 -translate-y-1/2': position == 'left',
                    }"
                    class="absolute flex items-center"
                >
                    <USelect
                        v-if="inputType == 'select'"
                        :model-value="inputValue[position]"
                        @update:model-value="handleChange(position, $event)"
                        :items="options"
                        size="xs"
                        :content="{
                            align: 'center',
                            side: 'bottom',
                            sideOffset: 0,
                        }"
                        :ui="{
                            content: 'dark min-w-20',
                        }"
                        arrow
                        variant="subtle"
                        class="dark w-20"
                        :value-key="valueField"
                        :label-key="labelField"
                    />

                    <BuilderColorInput
                        v-else-if="inputType == 'color'"
                        :show-label="false"
                        @change="handleChange(position, $event)"
                        :value="inputValue[position]"
                    />

                    <UInput
                        v-else-if="isCustom"
                        :id="id"
                        size="xs"
                        variant="subtle"
                        class="dark w-20"
                        placeholder="1px"
                        :model-value="inputValue[position]"
                        @update:model-value="handleChange(position, $event)"
                        :disabled="disabled"
                    />
                    <UInputNumber
                        v-else
                        :id="`${id}-1`"
                        :min="min"
                        :max="max"
                        :step="step"
                        :disabled="disabled"
                        orientation="horizontal"
                        :format-options="format"
                        :model-value="inputValue[position]"
                        @update:model-value="handleChange(position, $event)"
                        size="xs"
                        variant="subtle"
                        class="dark w-20"
                        placeholder="1px"
                    />
                </div>
            </template>

            <div class="rounded-md p-0">
                <div class="flex h-16 items-center justify-center rounded border border-neutral-700">
                    <BaseTooltip content="Link">
                        <UButton
                            size="xs"
                            @click.prevent="toggleLinked"
                            :color="isLinked ? 'primary' : 'neutral'"
                            :variant="isLinked ? 'solid' : 'subtle'"
                            :icon="isLinked ? 'ph:link' : 'ph:link-break'"
                        />
                    </BaseTooltip>
                </div>
            </div>
        </div>
    </UFormField>
</template>

<script setup lang="ts">
import { getId } from '@/helpers';
import BaseTooltip from '@modules/Builder/resources/components/base-tooltip.vue';
import BuilderColorInput from '@modules/Builder/resources/components/form/builder-color-input.vue';

interface Props {
    inputType?: 'text' | 'select' | 'color';
    inputValue: Record<string, any>;
    label?: string;
    labelField?: string;
    valueField?: string;
    rows?: number;
    unitOptions?: any;
    hasChanged?: boolean;
    unitOptionsIsObject?: boolean;
    unitOptionsValueField?: string;
    unitOptionsLabelField?: string;
    options?: any;
    inputIsSelect?: boolean;
    optionIsObject?: boolean;
    min?: number;
    max?: number;
    isDecimal?: boolean;
    isPercentage?: boolean;
    disabled?: boolean;
    positions?: string[];
}

const props = withDefaults(defineProps<Props>(), {
    inputType: 'text',
    unitOptions: () => [],
    unitOptionsIsObject: false,
    unitOptionsValueField: 'label',
    unitOptionsLabelField: 'value',
    labelField: 'label',
    valueField: 'value',
    hasChanged: false,
    inputIsSelect: false,
    optionIsObject: false,
    options: () => [],
    min: 0,
    max: 100,
    rows: 1,
    isPercentage: false,
    isDecimal: false,
    disabled: false,
    positions: () => ['top', 'right', 'bottom', 'left'],
});

const id = getId();

const isLinked = computed<boolean>(() => props.inputValue.isLinked || false)

const emit = defineEmits(['change', 'clear:hover']);

function getEmittableValue(key: string) {
    if (isNaN(props.inputValue[key])) {
        return 0;
    }
    return props.inputValue[key];
}

function handleChange(key: string, event: any) {
    if (isLinked.value && key !== 'unit') {
        emitAll(event);
    } else {
        if (key == 'unit' && event != 'custom') {
            emit('change', {
                ...props.inputValue,
                top: getEmittableValue('top'),
                right: getEmittableValue('right'),
                bottom: getEmittableValue('bottom'),
                left: getEmittableValue('left'),
                [key]: event,
            });
        } else {
            emit('change', { ...props.inputValue, [key]: event });
        }
    }
}

const isCustom = computed<boolean>(() => props.inputValue.unit == 'custom');

const format = computed(() => {
    let fmt = {};
    if (props.isDecimal) {
        fmt = { ...fmt, minimumFractionDigits: 1 };
    }
    if (props.isPercentage) {
        fmt = { ...fmt, style: 'percent' };
    }
    return fmt;
});

const step = computed(() => {
    if (props.isDecimal) {
        return 0.1;
    }
    return 1;
});


watch(() => props.inputValue.isLinked, (newValue) => {
    if(newValue == true){
        emitAll(props.inputValue.top);
    }
})

async function toggleLinked() {
    const currentlyUnLinked = !props.inputValue.isLinked || props.inputValue.isLinked == undefined
    emit('change', { ...props.inputValue, isLinked: currentlyUnLinked ? true : false });
}

function emitAll(value: any) {
    emit('change', {
        ...props.inputValue,
        top: value,
        right: value,
        bottom: value,
        left: value,
    });
}
</script>

<style scoped></style>
