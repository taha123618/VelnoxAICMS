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
          ></UButton>
        </BaseTooltip>
      </div>
    </template>
    <USelectMenu
      :id="id"
      :ui="{
        content: 'dark border border-neutral-700',
        input: 'p-2',
      }"
      arrow
      :placeholder="placeholder"
      :model-value="value"
      @update:model-value="emit('change', $event)"
      size="xs"
      :items="options"
      variant="subtle"
      class="dark w-full"
      :value-key="valueField"
      :label-key="labelField"
    >
      <template
        v-if="isFont"
        #item="{ item }"
      >
        <span
          class="text-base"
          :style="{ fontFamily: item }"
        >
          {{ item }}
        </span>
      </template>
    </USelectMenu>
  </UFormField>
</template>

<script setup lang="ts">
import { getId } from "@/helpers";
import BaseTooltip from "@modules/Builder/resources/components/base-tooltip.vue";
import { TLabelPosition } from "@modules/Builder/resources/scripts/types";

interface Props {
  value: string | number | undefined;
  inputClass?: string;
  label?: string;
  labelField?: string;
  valueField?: string;
  placeholder?: string;
  labelPosition?: TLabelPosition;
  options?: any;
  optionIsObject?: boolean;
  isFont?: boolean;
  hasChanged?: boolean;
}

withDefaults(defineProps<Props>(), {
  labelPosition: "top",
  labelField: "label",
  valueField: "value",
  inputClass: "",
  optionIsObject: false,
  isFont: false,
  hasChanged: false,
  options: () => [],
});

const id = getId();
const emit = defineEmits(["change", "clear:hover"]);
</script>

<style scoped></style>
