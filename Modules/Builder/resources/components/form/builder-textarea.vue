<template>
  <UFormField
    :label="label"
    :error="error"
    class="text-xs"
    :help="help"
    :ui="{
      label: 'block font-normal VelnoxAI-label',
      container: labelPosition == 'left' ? 'col-span-2 mt-0' : 'relative'
    }"
    :class="[
      labelPosition == 'left'
        ? 'grid grid-cols-3 gap-1 items-center'
        : 'flex flex-col gap-1',
    ]"
  >
    <UTextarea
      :id="id"
      size="xs"
      variant="subtle"
      class="dark w-full"
      :rows="rows"
      :maxrows="maxRows"
      :placeholder="placeholder"
      @update:model-value="emit('change', $event)"
      :disabled="disabled"
      :model-value="value"
    />
  </UFormField>
</template>

<script setup lang="ts">
import { getId } from "@/helpers";
import { TLabelPosition } from "@modules/Builder/resources/scripts/types";
interface Props {
  value: string | null | undefined;
  inputClass?: string;
  label?: string;
  error?: string;
  help?: string;
  rows?: number;
  maxRows?: number;
  disabled?: boolean;
  placeholder?: string;
  labelPosition?: TLabelPosition;
}

const id = getId()

withDefaults(defineProps<Props>(), {
  labelPosition: "top",
  disabled: false,
  rows: 3,
  maxRows: 6,
});

const emit = defineEmits(["change"]);
</script>

<style scoped></style>
