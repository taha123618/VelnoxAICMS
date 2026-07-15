<template>
    <form @submit.prevent="submit">
        <BaseSettingsPanel :title="`Page settings: ${page.title}`">
            <template #aside>
                <BaseTooltip
                    side="right"
                    content="Save changes"
                >
                    <UButton
                        @click.prevent="submit"
                        type="submit"
                        :loading="form.processing"
                        :disabled="!form.isDirty"
                        size="sm"
                        icon="ph:floppy-disk"
                    />
                </BaseTooltip>
            </template>

            <div class="space-y-4">
                <UFormField
                    label="Is front page"
                    class="grid grid-cols-2 items-center text-xs"
                >
                    <USwitch
                        size="sm"
                        v-model="form.isFrontpage"
                    />
                </UFormField>

                <BuilderSelect
                    label="Status"
                    label-position="left"
                    :options="statuses"
                    :value="form.status"
                    :error="form.errors.status"
                    @change="form.status = $event"
                />

                <BuilderSelect
                    label="Layout"
                    label-position="left"
                    :options="layouts"
                    label-field="name"
                    value-field="id"
                    :error="form.errors.layout"
                    option-is-object
                    :value="form.layout"
                    @change="form.layout = $event"
                />

                <div class="space-y-4 border border-neutral-800 p-2">
                    <h2 class="mb-2 text-sm leading-6">SEO</h2>
                    <BuilderInput
                        label="Page title"
                        :value="form.title"
                        :error="form.errors.title"
                        @change="form.title = $event"
                    />
                    <BuilderTextarea
                        label="Description"
                        :error="form.errors.description"
                        :value="form.description"
                        @change="form.description = $event"
                    />
                    <BuilderTagsInput
                        label="Keywords"
                        :error="form.errors.keywords"
                        :value="form.keywords"
                        @change="form.keywords = $event"
                    />
                </div>
            </div>
        </BaseSettingsPanel>
    </form>
</template>

<script setup lang="ts">
import BaseSettingsPanel from '@modules/Builder/resources/components/base-settings-panel.vue';
import BaseTooltip from '@modules/Builder/resources/components/base-tooltip.vue';
import BuilderInput from '@modules/Builder/resources/components/form/builder-input.vue';
import BuilderSelect from '@modules/Builder/resources/components/form/builder-select.vue';
import BuilderTextarea from '@modules/Builder/resources/components/form/builder-textarea.vue';
import BuilderTagsInput from '@modules/Builder/resources/components/form/builder-tags-input.vue';

import { usePage } from '@inertiajs/vue3';

const page = computed<Modules.Page.Data.PageData>(
    () => usePage().props.page as Modules.Page.Data.PageData,
);

const layouts = computed<Modules.Layout.Data.LayoutData[]>(
    () =>
        (usePage().props.layouts as Modules.Layout.Data.LayoutData[]).map(
            (layout: Modules.Layout.Data.LayoutData) => {
                return {
                    id: layout.id,
                    name: layout.name,
                };
            },
        ) as Modules.Layout.Data.LayoutData[],
);

const form = useForm({
    title: page.value.title,
    status: page.value.status,
    description: page.value.description,
    isFrontpage: page.value.isFrontpage,
    layout: page.value.layoutId,
    keywords: page.value.keywords || [],
});

const statuses: App.Enums.Status[] = ['Published', 'Draft'];

function submit() {
    form.put(route('admin.pages.update', page.value.id))
}
</script>

<style scoped></style>
