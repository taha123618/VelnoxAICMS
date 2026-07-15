<template>
    <form @submit.prevent="submit">
        <BaseSettingsPanel :title="post.title">
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
                <BuilderSelect
                    :error="form.errors.status"
                    label="Status"
                    label-position="left"
                    :options="statuses"
                    :value="form.status"
                    @change="form.status = $event"
                />

                <BuilderSelect
                    :error="form.errors.layout"
                    label="Layout"
                    label-position="left"
                    :options="layouts"
                    label-field="name"
                    value-field="id"
                    option-is-object
                    :value="form.layout"
                    @change="form.layout = $event"
                />

                <BuilderTextarea
                    :error="form.errors.excerpt"
                    label="Excerpt"
                    :value="form.excerpt"
                    @change="form.excerpt = $event"
                />

                <!-- prettier-ignore -->
                <BuilderImagePicker
                    label="Featured image"
                    :value="(form.featuredImage as string | undefined)"
                    @remove="form.featuredImage = ''"
                    @change="form.featuredImage = $event"
                />

                <div class="space-y-4 border border-neutral-800 p-2">
                    <h2 class="mb-2 text-sm leading-6">SEO</h2>
                    <BuilderInput
                        :error="form.errors.title"
                        label="Post title"
                        :value="form.title"
                        @change="form.title = $event"
                    />

                    <BuilderTextarea
                        :error="form.errors.description"
                        label="Description"
                        :value="form.description"
                        @change="form.description = $event"
                    />

                    <BuilderTagsInput
                        :error="form.errors.keywords"
                        label="Keywords"
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
import BuilderImagePicker from '@modules/Builder/resources/components/form/builder-image-picker.vue';
import BuilderInput from '@modules/Builder/resources/components/form/builder-input.vue';
import BuilderSelect from '@modules/Builder/resources/components/form/builder-select.vue';
import BuilderTextarea from '@modules/Builder/resources/components/form/builder-textarea.vue';
import BuilderTagsInput from '@modules/Builder/resources/components/form/builder-tags-input.vue';

import { usePage } from '@inertiajs/vue3';

const post = computed<Modules.Page.Data.PostData>(
    () => usePage().props.post as Modules.Page.Data.PostData,
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
        ) satisfies Modules.Layout.Data.LayoutData[],
);

const form = useForm<App.Data.Posts.PostUpdateRequest>({
    title: post.value.title,
    status: post.value.status,
    description: post.value.description,
    excerpt: post.value.excerpt,
    featuredImage: post.value.featuredImage,
    layout: post.value.layoutId,
    keywords: post.value.keywords || [],
});

const statuses: App.Enums.Status[] = ['Published', 'Draft'];

function submit() {
    form.put(route('admin.posts.meta.update', post.value.id));
}
</script>

<style scoped></style>
