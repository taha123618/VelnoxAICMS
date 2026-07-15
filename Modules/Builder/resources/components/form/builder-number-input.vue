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
      <div class="flex gap-0.5 items-center">
        <div class="leading-0x">{{ label }}</div>
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
    <UInputNumber
      :id="id"
      :min="min"
      :max="max"
      :format-options="format"
      :step="step"
      :orientation="showIcons ? 'horizontal' : 'vertical'"
      :disabled="disabled"
      :model-value="value"
      @update:model-value="emit('change', $event)"
      :placeholder="placeholder"
      size="xs"
      variant="subtle"
      class="dark w-full"
    />
  </UFormField>
</template>

<script setup lang="ts">
import BaseTooltip from "@modules/Builder/resources/components/base-tooltip.vue";
import { TLabelPosition } from "@modules/Builder/resources/scripts/types";
import { getId } from "@/helpers";
interface Props {
  value: number | undefined;
  inputClass?: string;
  label?: string;
  showIcons?: boolean;
  min?: number;
  max?: number;
  placeholder?: string;
  labelPosition?: TLabelPosition;
  isDecimal?: boolean;
  isPercentage?: boolean;
  disabled?: boolean;
  hasChanged?: boolean;
}

const id = getId()

const props = withDefaults(defineProps<Props>(), {
  labelPosition: "top",
  min: 0,
  max: 100,
  showIcons: true,
  isPercentage: false,
  isDecimal: false,
  disabled: false,
  hasChanged: false,
});

const emit = defineEmits(["change", "clear:hover"]);

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
    return 0.5;
  }
  return 1;
});
</script>

<style scoped></style>
