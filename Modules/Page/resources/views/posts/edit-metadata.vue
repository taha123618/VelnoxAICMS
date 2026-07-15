<template>
    <BaseModalPage
        is-dark
        is-slide-over
        ref="modalRef"
        v-model="showModal"
        title="Edit post info"
    >
        <form
            class="space-y-4"
            @submit.prevent="submit"
        >
            <BuilderInput
                label="Post title"
                label-position="left"
                :value="form.title"
                :error="form.errors.title"
                @change="form.title = $event"
            />

            <BuilderPermalinkEditor
                label-position="top"
                label="Permalink (URL)"
                :error="form.errors.slug"
                :model-value="form.slug"
                @update:model-value="form.slug = $event"
            />

            <BuilderSelect
                label="Category"
                label-position="left"
                :options="categories"
                label-field="name"
                value-field="id"
                :error="form.errors.category"
                option-is-object
                :value="form.category"
                @change="form.category = $event"
            />

            <BuilderTextarea
                :error="form.errors.excerpt"
                label="Excerpt"
                :value="form.excerpt"
                @change="form.excerpt = $event"
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

            <BuilderImagePicker
                label="Featured image"
                :value="(form.featuredImage as string | undefined)"
                @remove="form.featuredImage = ''"
                @change="form.featuredImage = $event"
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
            <UButton
                block
                type="submit"
                :loading="form.processing"
            >
                Save changes
            </UButton>
        </form>

        <template #footer>
            <div class="flex flex-col">
                <h1 class="text-white text-lg">SEO</h1>
                <h2 class="text-purple-400">{{ form.title }}</h2>
                <h2 class="text-green-300 text-xs">{{ pageContent.ziggy.url }}/{{ form.slug }}</h2>
                <p class="text-neutral-400 text-sm">{{ form.description }}</p>
            </div>
        </template>
    </BaseModalPage>
</template>

<script setup lang="ts">
import BuilderTextarea from '@modules/Builder/resources/components/form/builder-textarea.vue';
import BuilderInput from '@modules/Builder/resources/components/form/builder-input.vue';
import BuilderSelect from '@modules/Builder/resources/components/form/builder-select.vue';
import BuilderTagsInput from '@modules/Builder/resources/components/form/builder-tags-input.vue';
import BuilderImagePicker from '@modules/Builder/resources/components/form/builder-image-picker.vue';
import BaseModalPage from '@/components/BaseModalPage.vue';
import BuilderPermalinkEditor from '@modules/Builder/resources/components/form/builder-permalink-editor.vue';
import { useForm as usePrecognitionForm } from 'laravel-precognition-vue-inertia';
import { SharedData } from '@/types';


const { layouts, post, categories } = defineProps<{
    layouts: Modules.Layout.Data.LayoutData[];
    post: Modules.Page.Data.PostData;
    categories: Record<string, any>[]
}>();

const pageContent = usePage<SharedData>().props

const modalRef = ref<InstanceType<typeof BaseModalPage> | null>(null);

const form = usePrecognitionForm('put', route('admin.posts.meta.update', post.id), {
    title: post.title,
    slug: post.slug,
    category: post.categoryId,
    description: post.description,
    excerpt: post.excerpt,
    featuredImage: post.featuredImage,
    layout: post.layoutId,
    keywords: post.keywords || [],
});

watch(() => form.slug, () => {
    return form.validate('slug')
})

const showModal = ref<boolean>(true)


function submit() {
    form.submit({
        preserveScroll: true,
        onSuccess: () => {
            modalRef.value?.close()
        }
    });
}

</script>

<style scoped></style>
