<template>
    <BaseModalPage
        v-model="showModal"
        title="Edit Category"
    >
        <form
            @submit.prevent="submit"
            class="space-y-3"
        >
            <UFormField
                required
                label="Name"
                :error="form.errors.name"
            >
                <UInput
                    class="w-full"
                    placeholder="Category name"
                    v-model="form.name"
                />
            </UFormField>
            <template v-if="categories.length">
                <UFormField
                    label="Parent category"
                    :error="form.errors.parent"
                >
                    <USelect
                        placeholder="--none--"
                        class="w-full"
                        v-model="form.parent"
                        :items="categories"
                        label-key="name"
                        value-key="id"
                    />
                </UFormField>
            </template>
            <UFormField
                label="Description"
                :error="form.errors.description"
            >
                <UTextarea
                    class="w-full"
                    placeholder="Description"
                    v-model="form.description"
                />
            </UFormField>

            <div class="flex items-center justify-end">
                <UButton
                    :loading="form.processing"
                    :disabled="!form.isDirty"
                    type="submit"
                    size="sm"
                    label="Save changes"
                />
            </div>
        </form>
    </BaseModalPage>
</template>

<script setup lang="ts">
import BaseModalPage from '@/components/BaseModalPage.vue';

const { category, categories } = defineProps<{
    category: Modules.Category.Data.CategoryData,
    categories: Modules.Category.Data.CategoryData[]
}>()

const showModal = ref<boolean>(true)

const form = useForm({
    name: category.name,
    parent: category.parentId || undefined,
    description: category.description,
});

function submit() {
    form.put(route('admin.categories.update', [category.id]), {
        onSuccess: () => {
            showModal.value = false
        },
        onError: () => console.log('Error occured'),
    });
}
</script>

<style scoped></style>