<template>
  <div class="space-y-4">
    <BuilderInput
      label-position="top"
      :value="element.getProp('endpoint')"
      @change="element.setProps('endpoint', $event)"
      placeholder="/contacts"
      label="Submission endpoint"
    />

    <BuilderSelect
      label="After submit"
      label-position="left"
      option-is-object
      :options="afterSubmitOptions"
      @change="element.setProps('afterSubmit', $event)"
      :value="element.getProp('afterSubmit')"
    />

    <template v-if="element.getProp('afterSubmit') == 'redirect'">
      <div>
        <UFormField
          :ui="{
            label: 'block font-normal VelnoxAI-label',
          }"
          label="Redirect to"
        >
          <UButtonGroup class="w-full">
            <UInput
              size="xs"
              variant="subtle"
              class="w-full"
              placeholder="Pick page"
              readonly
              :model-value="element.getProp('redirectTo')"
              @click.prevent="launchLinkPicker"
            />
            <UButton
              size="xs"
              color="neutral"
              variant="subtle"
              icon="ph:link"
              @click.prevent="launchLinkPicker"
            />
          </UButtonGroup>
        </UFormField>
      </div>
    </template>
      <BuilderTextarea
        label-position="top"
        :value="element.getProp('successMessage')"
        @change="element.setProps('successMessage', $event)"
        placeholder="Thank you for your message. We will be in touch shortly."
        label="Success message"
      />
  </div>
</template>

<script setup lang="ts">
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';
import BuilderInput from '@modules/Builder/resources/components/form/builder-input.vue';
import BuilderSelect from '@modules/Builder/resources/components/form/builder-select.vue';
import { visitModal } from '@inertiaui/modal-vue'
import BuilderTextarea from '@modules/Builder/resources/components/form/builder-textarea.vue';

const { element } = defineProps<{ element: VelnoxAIElement }>();

const afterSubmitOptions = [
  { label: 'Show toast message', value: 'toast' },
  { label: 'Redirect to page', value: 'redirect' },
]

function launchLinkPicker() {
  visitModal(route('admin.links.picker'), {
    data: {
      href: element.getProp('redirectTo')
    },
    listeners: {
      insert(payload: Record<string, any>) {
        element.setProps('redirectTo', payload.href)
      }
    },
  })
}
</script>

<style scoped></style>
