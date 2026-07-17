<template>
  <div class="space-y-4">

    <!-- Form Input Settings -->
    <div v-if="element.type === 'form-input'" class="space-y-3">
      <BuilderInput label="Label" placeholder="Field Label" :value="element.getProp('label') || ''" @change="element.setProps('label', $event)" />
      <BuilderInput label="Placeholder" placeholder="Enter value..." :value="element.getProp('placeholder') || ''" @change="element.setProps('placeholder', $event)" />
      <BuilderInput label="Field Name (HTML)" placeholder="email" :value="element.getProp('name') || ''" @change="element.setProps('name', $event)" />
      <BuilderSelect
        label="Input Type"
        :options="['text','email','password','number','tel','url','date','time','search'].map(v => ({ label: v, value: v }))"
        :value="element.getProp('inputType') || 'text'"
        @change="element.setProps('inputType', $event)"
      />
      <BuilderInput label="Help Text" placeholder="Optional hint for user" :value="element.getProp('helpText') || ''" @change="element.setProps('helpText', $event)" />
      <BuilderSelect
        label="Required"
        :options="[{ label: 'Yes', value: 'true' }, { label: 'No', value: 'false' }]"
        :value="element.getProp('required') ? 'true' : 'false'"
        @change="element.setProps('required', $event === 'true')"
      />
    </div>

    <!-- Form Textarea Settings -->
    <div v-else-if="element.type === 'form-textarea'" class="space-y-3">
      <BuilderInput label="Label" placeholder="Message" :value="element.getProp('label') || ''" @change="element.setProps('label', $event)" />
      <BuilderInput label="Placeholder" placeholder="Type your message..." :value="element.getProp('placeholder') || ''" @change="element.setProps('placeholder', $event)" />
      <BuilderInput label="Field Name (HTML)" placeholder="message" :value="element.getProp('name') || ''" @change="element.setProps('name', $event)" />
      <BuilderInput label="Rows" placeholder="4" :value="element.getProp('rows') || ''" @change="element.setProps('rows', $event)" />
      <BuilderInput label="Help Text" placeholder="Optional hint" :value="element.getProp('helpText') || ''" @change="element.setProps('helpText', $event)" />
      <BuilderSelect
        label="Required"
        :options="[{ label: 'Yes', value: 'true' }, { label: 'No', value: 'false' }]"
        :value="element.getProp('required') ? 'true' : 'false'"
        @change="element.setProps('required', $event === 'true')"
      />
    </div>

    <!-- Form Select Settings -->
    <div v-else-if="element.type === 'form-select'" class="space-y-3">
      <BuilderInput label="Label" placeholder="Select Option" :value="element.getProp('label') || ''" @change="element.setProps('label', $event)" />
      <BuilderInput label="Placeholder Option" placeholder="Choose one..." :value="element.getProp('placeholder') || ''" @change="element.setProps('placeholder', $event)" />
      <BuilderInput label="Field Name (HTML)" placeholder="option" :value="element.getProp('name') || ''" @change="element.setProps('name', $event)" />
      <div class="space-y-1">
        <label class="text-[11px] font-semibold text-neutral-400 uppercase tracking-wider">Options (one per line)</label>
        <textarea
          :value="element.getProp('options') || ''"
          @change="element.setProps('options', ($event.target as HTMLTextAreaElement).value)"
          rows="4"
          placeholder="Option A&#10;Option B&#10;Option C"
          class="w-full px-3 py-2 bg-neutral-900 border border-neutral-700 rounded-lg text-xs text-white placeholder-neutral-500 outline-none focus:border-primary resize-none"
        />
      </div>
      <BuilderSelect
        label="Required"
        :options="[{ label: 'Yes', value: 'true' }, { label: 'No', value: 'false' }]"
        :value="element.getProp('required') ? 'true' : 'false'"
        @change="element.setProps('required', $event === 'true')"
      />
    </div>

    <!-- Form Checkbox Settings -->
    <div v-else-if="element.type === 'form-checkbox'" class="space-y-3">
      <BuilderInput label="Label / Agreement Text" placeholder="I agree to the terms" :value="element.getProp('label') || ''" @change="element.setProps('label', $event)" />
      <BuilderInput label="Field Name (HTML)" placeholder="agree" :value="element.getProp('name') || ''" @change="element.setProps('name', $event)" />
      <BuilderInput label="Help Text" placeholder="Optional hint" :value="element.getProp('helpText') || ''" @change="element.setProps('helpText', $event)" />
      <BuilderSelect
        label="Checked by Default"
        :options="[{ label: 'Yes', value: 'true' }, { label: 'No', value: 'false' }]"
        :value="element.getProp('defaultChecked') ? 'true' : 'false'"
        @change="element.setProps('defaultChecked', $event === 'true')"
      />
      <BuilderSelect
        label="Required"
        :options="[{ label: 'Yes', value: 'true' }, { label: 'No', value: 'false' }]"
        :value="element.getProp('required') ? 'true' : 'false'"
        @change="element.setProps('required', $event === 'true')"
      />
    </div>

    <!-- Form Radio Settings -->
    <div v-else-if="element.type === 'form-radio'" class="space-y-3">
      <BuilderInput label="Group Label" placeholder="Choose Option" :value="element.getProp('label') || ''" @change="element.setProps('label', $event)" />
      <BuilderInput label="Field Name (HTML)" placeholder="choice" :value="element.getProp('name') || ''" @change="element.setProps('name', $event)" />
      <div class="space-y-1">
        <label class="text-[11px] font-semibold text-neutral-400 uppercase tracking-wider">Choices (one per line)</label>
        <textarea
          :value="element.getProp('options') || ''"
          @change="element.setProps('options', ($event.target as HTMLTextAreaElement).value)"
          rows="3"
          placeholder="Choice A&#10;Choice B&#10;Choice C"
          class="w-full px-3 py-2 bg-neutral-900 border border-neutral-700 rounded-lg text-xs text-white placeholder-neutral-500 outline-none focus:border-primary resize-none"
        />
      </div>
    </div>

    <!-- Form Switch Settings -->
    <div v-else-if="element.type === 'form-switch'" class="space-y-3">
      <BuilderInput label="Label" placeholder="Enable Feature" :value="element.getProp('label') || ''" @change="element.setProps('label', $event)" />
      <BuilderInput label="Field Name (HTML)" placeholder="feature" :value="element.getProp('name') || ''" @change="element.setProps('name', $event)" />
      <BuilderInput label="Help Text" placeholder="Optional description" :value="element.getProp('helpText') || ''" @change="element.setProps('helpText', $event)" />
      <BuilderSelect
        label="On by Default"
        :options="[{ label: 'Yes', value: 'true' }, { label: 'No', value: 'false' }]"
        :value="element.getProp('defaultChecked') ? 'true' : 'false'"
        @change="element.setProps('defaultChecked', $event === 'true')"
      />
    </div>

    <!-- Form Range Settings -->
    <div v-else-if="element.type === 'form-range'" class="space-y-3">
      <BuilderInput label="Label" placeholder="Range Slider" :value="element.getProp('label') || ''" @change="element.setProps('label', $event)" />
      <BuilderInput label="Min Value" placeholder="0" :value="element.getProp('min') || ''" @change="element.setProps('min', $event)" />
      <BuilderInput label="Max Value" placeholder="100" :value="element.getProp('max') || ''" @change="element.setProps('max', $event)" />
      <BuilderInput label="Default Value" placeholder="50" :value="element.getProp('defaultValue') || ''" @change="element.setProps('defaultValue', $event)" />
      <BuilderInput label="Field Name (HTML)" placeholder="range" :value="element.getProp('name') || ''" @change="element.setProps('name', $event)" />
    </div>

    <!-- Form Color Settings -->
    <div v-else-if="element.type === 'form-color'" class="space-y-3">
      <BuilderInput label="Label" placeholder="Pick a Color" :value="element.getProp('label') || ''" @change="element.setProps('label', $event)" />
      <BuilderInput label="Default Color (hex)" placeholder="#6366f1" :value="element.getProp('defaultColor') || ''" @change="element.setProps('defaultColor', $event)" />
      <BuilderInput label="Field Name (HTML)" placeholder="color" :value="element.getProp('name') || ''" @change="element.setProps('name', $event)" />
    </div>

  </div>
</template>

<script setup lang="ts">
import BuilderInput from '@modules/Builder/resources/components/form/builder-input.vue';
import BuilderSelect from '@modules/Builder/resources/components/form/builder-select.vue';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';

defineProps<{ element: ZioraElement }>();
</script>
