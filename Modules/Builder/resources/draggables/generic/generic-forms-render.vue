<template>
  <!-- Form Input Render -->
  <div v-if="element.type === 'form-input'"
       v-bind="elementAttributes"
       :class="cn(className, customClassNames, animationClass, 'w-full space-y-1.5')">
    <label :for="element.props.fieldId || element.id" class="block text-sm font-semibold text-neutral-800 dark:text-neutral-200">
      {{ element.getProp('label') || 'Field Label' }}
      <span v-if="element.getProp('required')" class="text-red-500 ml-0.5">*</span>
    </label>
    <input
      :id="element.props.fieldId || element.id"
      :type="element.getProp('inputType') || 'text'"
      :name="element.getProp('name') || element.id"
      :placeholder="element.getProp('placeholder') || ''"
      :required="!!element.getProp('required')"
      class="w-full px-4 py-2.5 bg-white dark:bg-neutral-900 border border-neutral-300 dark:border-neutral-700 rounded-lg text-sm text-neutral-900 dark:text-white placeholder-neutral-400 outline-none focus:ring-2 focus:ring-primary/50 transition"
    />
    <p v-if="element.getProp('helpText')" class="text-xs text-neutral-500">{{ element.getProp('helpText') }}</p>
  </div>

  <!-- Form Textarea Render -->
  <div v-else-if="element.type === 'form-textarea'"
       v-bind="elementAttributes"
       :class="cn(className, customClassNames, animationClass, 'w-full space-y-1.5')">
    <label :for="element.props.fieldId || element.id" class="block text-sm font-semibold text-neutral-800 dark:text-neutral-200">
      {{ element.getProp('label') || 'Message' }}
      <span v-if="element.getProp('required')" class="text-red-500 ml-0.5">*</span>
    </label>
    <textarea
      :id="element.props.fieldId || element.id"
      :name="element.getProp('name') || element.id"
      :placeholder="element.getProp('placeholder') || 'Type your message...'"
      :rows="parseInt(element.getProp('rows') || '4', 10)"
      :required="!!element.getProp('required')"
      class="w-full px-4 py-2.5 bg-white dark:bg-neutral-900 border border-neutral-300 dark:border-neutral-700 rounded-lg text-sm text-neutral-900 dark:text-white placeholder-neutral-400 outline-none focus:ring-2 focus:ring-primary/50 transition resize-y"
    />
    <p v-if="element.getProp('helpText')" class="text-xs text-neutral-500">{{ element.getProp('helpText') }}</p>
  </div>

  <!-- Form Select Render -->
  <div v-else-if="element.type === 'form-select'"
       v-bind="elementAttributes"
       :class="cn(className, customClassNames, animationClass, 'w-full space-y-1.5')">
    <label :for="element.props.fieldId || element.id" class="block text-sm font-semibold text-neutral-800 dark:text-neutral-200">
      {{ element.getProp('label') || 'Select Option' }}
      <span v-if="element.getProp('required')" class="text-red-500 ml-0.5">*</span>
    </label>
    <div class="relative">
      <select
        :id="element.props.fieldId || element.id"
        :name="element.getProp('name') || element.id"
        :required="!!element.getProp('required')"
        class="w-full appearance-none px-4 py-2.5 pr-10 bg-white dark:bg-neutral-900 border border-neutral-300 dark:border-neutral-700 rounded-lg text-sm text-neutral-900 dark:text-white outline-none focus:ring-2 focus:ring-primary/50 transition cursor-pointer"
      >
        <option value="">{{ element.getProp('placeholder') || 'Select an option...' }}</option>
        <option v-for="opt in selectOptions" :key="opt" :value="opt">{{ opt }}</option>
      </select>
      <UIcon name="ph:caret-down-bold" class="absolute right-3 top-1/2 -translate-y-1/2 size-4 text-neutral-400 pointer-events-none" />
    </div>
  </div>

  <!-- Form Checkbox Render -->
  <label v-else-if="element.type === 'form-checkbox'"
         v-bind="elementAttributes"
         :class="cn(className, customClassNames, animationClass, 'flex items-start gap-3 cursor-pointer group')">
    <input
      type="checkbox"
      :name="element.getProp('name') || element.id"
      :checked="!!element.getProp('defaultChecked')"
      :required="!!element.getProp('required')"
      class="mt-0.5 size-5 shrink-0 accent-primary rounded cursor-pointer"
    />
    <div class="min-w-0">
      <span class="text-sm font-medium text-neutral-800 dark:text-neutral-200 group-hover:text-primary transition">{{ element.getProp('label') || 'I agree to the terms and conditions' }}</span>
      <p v-if="element.getProp('helpText')" class="text-xs text-neutral-500 mt-0.5">{{ element.getProp('helpText') }}</p>
    </div>
  </label>

  <!-- Form Radio Render -->
  <fieldset v-else-if="element.type === 'form-radio'"
            v-bind="elementAttributes"
            :class="cn(className, customClassNames, animationClass, 'w-full space-y-2')">
    <legend class="text-sm font-semibold text-neutral-800 dark:text-neutral-200 mb-2">{{ element.getProp('label') || 'Choose Option' }}</legend>
    <label v-for="(opt, idx) in radioOptions" :key="idx" class="flex items-center gap-3 cursor-pointer group">
      <input
        type="radio"
        :name="element.getProp('name') || element.id"
        :value="opt"
        :checked="idx === 0"
        class="size-4 accent-primary cursor-pointer"
      />
      <span class="text-sm text-neutral-700 dark:text-neutral-300 group-hover:text-primary transition">{{ opt }}</span>
    </label>
  </fieldset>

  <!-- Form Switch Render -->
  <label v-else-if="element.type === 'form-switch'"
         v-bind="elementAttributes"
         :class="cn(className, customClassNames, animationClass, 'flex items-center justify-between gap-3 cursor-pointer')">
    <div class="min-w-0">
      <span class="text-sm font-semibold text-neutral-800 dark:text-neutral-200">{{ element.getProp('label') || 'Enable Feature' }}</span>
      <p v-if="element.getProp('helpText')" class="text-xs text-neutral-500 mt-0.5">{{ element.getProp('helpText') }}</p>
    </div>
    <div class="relative shrink-0">
      <input type="checkbox" :name="element.getProp('name') || element.id" :checked="!!element.getProp('defaultChecked')" class="sr-only" />
      <div class="w-11 h-6 rounded-full transition" :class="element.getProp('defaultChecked') ? 'bg-primary' : 'bg-neutral-300 dark:bg-neutral-700'">
        <div class="absolute top-1 size-4 bg-white rounded-full shadow transition-transform"
             :class="element.getProp('defaultChecked') ? 'translate-x-5.5' : 'translate-x-1'" />
      </div>
    </div>
  </label>

  <!-- Form Range Slider Render -->
  <div v-else-if="element.type === 'form-range'"
       v-bind="elementAttributes"
       :class="cn(className, customClassNames, animationClass, 'w-full space-y-2')">
    <div class="flex items-center justify-between">
      <label class="text-sm font-semibold text-neutral-800 dark:text-neutral-200">{{ element.getProp('label') || 'Range' }}</label>
      <span class="text-sm font-bold text-primary">{{ element.getProp('defaultValue') || '50' }}</span>
    </div>
    <input
      type="range"
      :min="element.getProp('min') || '0'"
      :max="element.getProp('max') || '100'"
      :value="element.getProp('defaultValue') || '50'"
      :name="element.getProp('name') || element.id"
      class="w-full h-2 rounded-full accent-primary cursor-pointer"
    />
    <div class="flex justify-between text-xs text-neutral-400">
      <span>{{ element.getProp('min') || '0' }}</span>
      <span>{{ element.getProp('max') || '100' }}</span>
    </div>
  </div>

  <!-- Form Color Picker Render -->
  <div v-else-if="element.type === 'form-color'"
       v-bind="elementAttributes"
       :class="cn(className, customClassNames, animationClass, 'w-full space-y-1.5')">
    <label :for="element.props.fieldId || element.id" class="block text-sm font-semibold text-neutral-800 dark:text-neutral-200">{{ element.getProp('label') || 'Pick a Color' }}</label>
    <div class="flex items-center gap-2">
      <input
        :id="element.props.fieldId || element.id"
        type="color"
        :name="element.getProp('name') || element.id"
        :value="element.getProp('defaultColor') || '#6366f1'"
        class="size-10 rounded-lg border-2 border-neutral-300 dark:border-neutral-700 cursor-pointer"
      />
      <input
        type="text"
        :value="element.getProp('defaultColor') || '#6366f1'"
        class="flex-1 px-3 py-2 bg-white dark:bg-neutral-900 border border-neutral-300 dark:border-neutral-700 rounded-lg text-sm font-mono text-neutral-900 dark:text-white outline-none focus:ring-2 focus:ring-primary/50 transition"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import { cn } from '@modules/Builder/resources/scripts/utils';
import { useElement } from '@modules/Builder/resources/scripts/use-element';
import { computed } from 'vue';

const { element } = defineProps<{
  element: ZioraElement;
}>();

const { customClassNames, animationClass, className } = useElement(element);

const elementAttributes = computed(() => {
  const customAttrs = element.getProp('custom.attributes') || {};
  return { ...customAttrs };
});

const selectOptions = computed<string[]>(() => {
  const raw = element.getProp('options');
  if (Array.isArray(raw)) return raw;
  if (typeof raw === 'string') return raw.split('\n').map((s: string) => s.trim()).filter(Boolean);
  return ['Option A', 'Option B', 'Option C'];
});

const radioOptions = computed<string[]>(() => {
  const raw = element.getProp('options');
  if (Array.isArray(raw)) return raw;
  if (typeof raw === 'string') return raw.split('\n').map((s: string) => s.trim()).filter(Boolean);
  return ['Choice A', 'Choice B', 'Choice C'];
});
</script>

<style scoped></style>
