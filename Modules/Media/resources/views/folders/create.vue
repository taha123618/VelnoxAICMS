<template>
    <BaseModalPage
        v-model="showModal"
        title="New Folder"
    >
        <form
            class="space-y-2"
            @submit.prevent="submit"
        >
            <UFormField
                required
                :error="form.errors.name"
                label="Name"
            >
                <UInput
                    v-model="form.name"
                    placeholder="Name"
                    class="w-full"
                />
            </UFormField>
            <UButton
                @click.prevent="submit"
                type="submit"
                :loading="form.processing"
            >
                Create
            </UButton>
        </form>
    </BaseModalPage>

</template>

<script setup lang="ts">
import BaseModalPage from '@/components/BaseModalPage.vue';


const { parentFolder } = defineProps<{
    parentFolder?: string;
}>();

const showModal = ref<boolean>(true)
const emit = defineEmits(['closed'])

const form = useForm({
    name: '',
    parentId: parentFolder,
});


function submit() {
    form.post(route('admin.folders.store', { parent: parentFolder }), {
        onSuccess: () => {
            form.reset();
            showModal.value = false;
            emit('closed')
        }
    });
}
</script>

<style scoped></style>
