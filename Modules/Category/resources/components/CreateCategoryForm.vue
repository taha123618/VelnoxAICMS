<template>
    <UCard :ui="{
        root: 'h-full',
        header: 'p-4 sm:p-4 font-bold text-sm',
        body: 'p-4 sm:p-4',
    }">
        <template #header>Add Category </template>
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
                    :disabled="!authUser.can.create_categories"
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
                        :disabled="!authUser.can.create_categories"
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
                    :disabled="!authUser.can.create_categories"
                    class="w-full"
                    placeholder="Description"
                    v-model="form.description"
                />
            </UFormField>

            <div class="flex items-center justify-end">
                <UButton
                    :disabled="!authUser.can.create_categories"
                    :loading="form.processing"
                    type="submit"
                    size="sm"
                    label="Add category"
                />
            </div>
        </form>
    </UCard>
</template>

<script setup lang="ts">
import { useAuth } from '@modules/Auth/resources/composables/use-auth';


defineProps<{
    categories: Modules.Category.Data.CategoryData[]
}>()

const authUser = useAuth()

const form = useForm({
    name: '',
    parent: '',
    description: '',
});

function submit() {
    form.post(route('admin.categories.store'), {
        onSuccess: () => {
            form.reset();
        },
        onError: () => console.log('Error occured'),
    });
}
</script>

<style scoped></style>