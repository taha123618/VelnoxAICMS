<template>
    <BaseModalPage
        v-model="showModal"
        title="Rename folder"
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
                Rename
            </UButton>
        </form>
    </BaseModalPage>
</template>

<script setup lang="ts">
import BaseModalPage from '@/components/BaseModalPage.vue';


const { folder } = defineProps<{
    folder: Modules.Media.Data.FolderData;
}>();

const emit = defineEmits(['closed'])
const showModal = ref<boolean>(true)

const form = useForm({
    name: folder.name
});


function submit() {
    form.put(route('admin.folders.update', { folder: folder.id }), {
        onSuccess: () => {
            form.reset();
            showModal.value = false
            emit('closed')
        }
    });
}
</script>

<style scoped></style>
