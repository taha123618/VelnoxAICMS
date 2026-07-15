<template>
  <UFormField
    :label="label"
    :error="error"
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
          />
        </BaseTooltip>
      </div>
    </template>
    <UInput
      :id="id"
      size="xs"
      variant="subtle"
      class="w-full"
      :placeholder="placeholder"
      @update:model-value="emit('change', $event)"
      :disabled="disabled"
      :model-value="value"
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
  error?: string;
  label?: string;
  disabled?: boolean;
  hasChanged?: boolean;
  type?: string;
  placeholder?: string;
  labelPosition?: TLabelPosition;
}

const id = getId()

withDefaults(defineProps<Props>(), {
  labelPosition: "top",
  type: "text",
  disabled: false,
  hasChanged: false
});

const emit = defineEmits(["change", "clear:hover"]);
</script>

<style scoped></style>
