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
    <Codemirror
      ref="codemirror"
      :model-value="value"
      placeholder=".example{ color: red; }"
      :extensions="[css(), oneDark]"
      :style="{ minHeight: '200px' }"
      :basic-setup="true"
      :indent-with-tab="true"
      :tab-size="2"
      @update:modelValue="handleChange"
    />
  </UFormField>
</template>

<script setup lang="ts">
 import { Codemirror } from 'vue-codemirror'
import { css } from "@codemirror/lang-css";
import { oneDark } from '@codemirror/theme-one-dark';
import { ref } from "vue";
import { TLabelPosition } from '@modules/Builder/resources/scripts/types';

interface Props {
  value: string | undefined;
  inputClass?: string;
  label?: string;
  disabled?: boolean;
  placeholder?: string;
  labelPosition?: TLabelPosition;
}

withDefaults(defineProps<Props>(), {
  labelPosition: "top",
  disabled: false,
});

const emit = defineEmits(["change"]);

const codemirror = ref();

const handleChange = (value: string) => {
  emit("change", value);
};
</script>

<style scoped></style>
