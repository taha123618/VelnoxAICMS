<template>
  <UFormField
    :label="label"
    class="text-xs"
    :ui="{
      label: 'block font-normal ziora-label',
      container: labelPosition == 'left' ? 'col-span-2 mt-0' : 'relative'
    }"
    :class="[
      labelPosition == 'left'
        ? 'grid grid-cols-3 gap-1 items-center'
        : 'flex flex-col gap-1',
    ]"
  >
    <template #label>
      <div class="flex text-xs gap-0.5 items-center">
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
    <div class="inline-flex col-span-2 gap-1">
      <template v-if="selectValue == 'custom' || inputType != 'number'">
        <UInput
          :id="id"
          size="xs"
          placeholder="10px"
          variant="subtle"
          class="dark w-2/3"
          @update:model-value="emit('change:input', $event)"
          :disabled="disabled"
          :model-value="inputValue"
        />
      </template>
      <template v-else>
        <UInputNumber
          :id="id"
          :min="min"
          :max="max"
          :format-options="format"
          :step="step"
          :orientation="showIcons ? 'horizontal' : 'horizontal'"
          :disabled="disabled"
          :model-value="Number(inputValue)"
          @update:model-value="emit('change:input', $event)"
          :placeholder="placeholder"
          size="xs"
          variant="subtle"
          class="dark text-xs w-3/5"
        />
      </template>
      <USelect
        :id="id"
        :placeholder="placeholder"
        :model-value="selectValue"
        @update:model-value="emit('change:select', $event)"
        size="xs"
        :content="{
          align: 'center',
          side: 'bottom',
          sideOffset: 0,
        }"
        :ui="{
          content: 'dark min-w-24',
        }"
        arrow
        :items="options"
        variant="subtle"
        class="dark w-2/5"
        :value-key="valueField"
        :label-key="labelField"
      />
    </div>
  </UFormField>
</template>

<script setup lang="ts">
import { TLabelPosition } from "@modules/Builder/resources/scripts/types";
import BaseTooltip from "@modules/Builder/resources/components/base-tooltip.vue";

import { computed } from "vue";
import { getId } from "@/helpers";
interface Props {
  inputType?: "number" | "text";
  inputValue: string | number | undefined;
  selectValue: string | number | undefined | null;
  selectClass?: string;
  inputClass?: string;
  label?: string;
  placeholder?: string;
  labelField?: string;
  valueField?: string;
  inputPlaceholder?: string;
  selectPlaceholder?: string;
  labelPosition?: TLabelPosition;
  options?: any;
  optionIsObject?: boolean;
  showIcons?: boolean;
  disabledSelect?: boolean;
  min?: number;
  max?: number;
  isDecimal?: boolean;
  isPercentage?: boolean;
  disabled?: boolean;
  hasChanged?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  labelPosition: "top",
  labelField: "label",
  valueField: "label",
  selectClass: "",
  disabledSelect: false,
  inputType: "number",
  optionIsObject: false,
  options: () => [],
  min: 0,
  max: 100,
  showIcons: true,
  isPercentage: false,
  isDecimal: false,
  disabled: false,
  hasChanged: false,
});

const id = getId();

const emit = defineEmits(["change:input", "clear:hover", "change:select"]);

const format = computed(() => {
  let fmt = {};
  if (props.isDecimal) {
    fmt = { ...fmt, minimumFractionDigits: 1 };
  }
  if (props.isPercentage) {
    fmt = { ...fmt, style: "percent" };
  }

  return fmt;
});

const step = computed(() => {
  if (props.isDecimal) {
    return 0.1;
  }
  return 1;
});

watch(() => props.selectValue, (newValue, oldValue) => {
  if (oldValue == 'custom') {
    console.log("changing")
    emit('change:input', props.inputType == 'number' ? 5 : null)
  }
})
</script>

<style scoped></style>
