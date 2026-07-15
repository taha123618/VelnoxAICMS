<template>
  <UFormField
    :label="label"
    class="text-xs"
    :error="error"
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
    <USelect
      :id="id"
      :disabled="disabled"
      :placeholder="placeholder"
      :multiple="multiple"
      :model-value="value"
      @update:model-value="emit('change', $event)"
      size="xs"
      :ui="{
        content: 'dark z-[1000]',
      }"
      arrow
      :items="options"
      variant="subtle"
      class="w-full"
      :value-key="valueField"
      :label-key="labelField"
    />
  </UFormField>
</template>

<script setup lang="ts">
import BaseTooltip from "@modules/Builder/resources/components/base-tooltip.vue";
import { TLabelPosition } from "@modules/Builder/resources/scripts/types";
import { getId } from "@/helpers";

interface Props {
  value: string | number | undefined;
  inputClass?: string;
  label?: string;
  error?: string;
  labelField?: string;
  valueField?: string;
  placeholder?: string;
  labelPosition?: TLabelPosition;
  options?: any;
  optionIsObject?: boolean;
  multiple?: boolean;
  disabled?: boolean;
  hasChanged?: boolean;
}

withDefaults(defineProps<Props>(), {
  labelPosition: "top",
  labelField: "label",
  valueField: "value",
  inputClass: "",
  optionIsObject: false,
  multiple: false,
  disabled: false,
  hasChanged: false,
  options: () => [],
});

const id = getId()
const emit = defineEmits(["change", "clear:hover"]);
</script>

<style scoped></style>
