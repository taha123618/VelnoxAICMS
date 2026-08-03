<template>
    <div class="space-y-4">
        <BuilderInput
            label-position="left"
            :value="element.getProp('content.innerText')"
            @change="element.setProps('content.innerText', $event)"
            label="Text"
        />

        <div>
            <UFormField
                :ui="{
                    label: 'block font-normal VelnoxAI-label',
                }"
                label="Pick link"
                class="grid grid-cols-3 gap-1 items-center"
            >
                <UButton
                    :ui="{
                        leadingIcon: 'size-4',
                    }"
                    @click.prevent="launchLinkPicker"
                    label="Choose..."
                    size="xs"
                    icon="ph:link"
                    color="neutral"
                    variant="subtle"
                />
            </UFormField>

            <ULink
                :href="element.getProp('content.href')"
                class="bg-warning/40 truncate text-xs px-0.5 rounded text-neutral-200"
            >
                {{ element.getProp('content.href') }}
            </ULink>
        </div>

        <template v-if="element.getProp('content.linkType') != 'none'">
            <BuilderSelect
                label="Link target"
                label-position="left"
                option-is-object
                :options="linkTargets"
                @change="element.setProps('content.target', $event)"
                :value="element.getProp('content.target')"
            />
        </template>

        <VisibilitySettings
            :options="['block', 'inline', 'none']"
            :element="element"
        />
    </div>
</template>

<script setup lang="ts">
import BuilderInput from '@modules/Builder/resources/components/form/builder-input.vue';
import BuilderSelect from '@modules/Builder/resources/components/form/builder-select.vue';
import VisibilitySettings from '@modules/Builder/resources/components/settings/visibility-settings.vue';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';
import { visitModal } from '@inertiaui/modal-vue'

function launchLinkPicker() {
    visitModal(route('admin.links.picker'), {
        data: {
            linkType: element.getProp('content.linkType'),
            href: element.getProp('content.href')
        },
        listeners: {
            insert(payload: Record<string, any>) {
                element.setProps('content.linkType', payload.linkType)
                element.setProps('content.href', payload.href)
            }
        },
    })
}


const { element } = defineProps<{
    element: VelnoxAIElement;
}>();

const linkTargets = [
    { value: '_self', label: 'Self' },
    { value: '_blank', label: 'Blank' },
];

</script>

<style scoped></style>
