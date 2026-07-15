<template>
    <div v-if="!!selectedMedia">
        <div
            v-if="selectedMedia?.isDirectory"
            :key="selectedMedia?.id"
            class="space-y-4 animate-in fade-in-50 duration-500"
        >
            <div class="flex rounded items-center mx-auto mb-2 justify-center w-full h-32 bg-neutral-100 dark:bg-neutral-800">
                <UIcon
                    name="ph:folder-fill"
                    class="size-20 text-orange-300"
                />
            </div>
            <div>
                <h1 class="font-semibold">{{ selectedMedia?.name }}</h1>
                <ul class="text-xs space-y-0.5">
                    <li>Total content: {{ selectedMedia?.contentCount || 'Empty' }}</li>
                    <li>Created by: {{ selectedMedia?.owner }}</li>
                    <li>Created: {{ selectedMedia?.createdAt }}</li>
                    <li>Modified: {{ selectedMedia?.modifiedAt }}</li>
                </ul>
            </div>
            <div class="flex items-center gap-2">
                <UButton
                    @click="handleDelete"
                    size="xs"
                    variant="soft"
                    color="error"
                    icon="ph:trash"
                    label="Delete"
                />
                <UButton
                    size="xs"
                    @click="visitModal(route('admin.folders.edit', selectedMedia?.id), {
                        onClose: () => {
                            onRename()
                        },
                    })"
                    variant="soft"
                    color="primary"
                    icon="ph:pencil"
                    label="Rename"
                />
            </div>
        </div>

        <div
            class="space-y-4 animate-in fade-in-50 duration-500"
            v-else
        >
            <div class="flex w-full items-center overflow-hidden justify-center rounded-md border border-neutral-200 dark:border-neutral-800">
                <img
                    class="object-contain"
                    :src="selectedMedia?.thumbnail"
                />
            </div>
            <div>
                <h1 class="font-semibold">{{ selectedMedia?.name }}</h1>
                <ul class="text-xs space-y-0.5">
                    <li>Size: {{ selectedMedia?.size }}</li>
                    <li>Created: {{ selectedMedia?.createdAt }}</li>
                    <li>Modified: {{ selectedMedia?.modifiedAt }}</li>
                </ul>
            </div>

            <div class="flex items-center gap-2">
                <UButton
                    v-if="isModalPage"
                    size="xs"
                    @click="onInsert"
                    variant="soft"
                    color="primary"
                    icon="ph:pencil"
                    label="Insert file"
                />

                <UButton
                    v-else
                    @click="handleDelete"
                    size="xs"
                    :disabled="!authUser.can.create_folders"
                    variant="soft"
                    color="error"
                    icon="ph:trash"
                    label="Delete"
                />

            </div>
        </div>

        <BaseConfirmationModal
            v-model="isRevealed"
            @close="cancel"
            @cancel="cancel"
            @confirm="confirm"
        />
    </div>
</template>

<script setup lang="ts">
import { visitModal } from '@inertiaui/modal-vue'
import { useConfirmDialog } from '@vueuse/core';
import { useAuth } from '@modules/Auth/resources/composables/use-auth';
import { useFileManager } from '@modules/Media/resources/scripts/use-filemanager';

const { content, isModalPage } = defineProps<{
    content: Record<string, any>[];
    isModalPage: boolean;
}>();

const authUser = useAuth()

const { selectedMedia, setSelectedMedia, modalRef, showModal } = useFileManager()


const { isRevealed, cancel, confirm, reveal } = useConfirmDialog();

function onInsert() {
    modalRef.value?.emit('selected', selectedMedia.value?.url)
    setSelectedMedia()
    showModal.value = false;
    modalRef.value?.close();
}

function onRename() {
    if (selectedMedia.value) {
        setSelectedMedia(content.find(c => c.id == selectedMedia.value!.id) || null)
    }

    if (isModalPage) {
        modalRef.value?.reload()
    }
}

async function handleDelete() {
    if (!selectedMedia.value) {
        return
    }

    const { isCanceled } = await reveal();

    if (isCanceled) {
        return;
    }

    if (selectedMedia.value.isDirectory) {
        router.delete(route('admin.folders.destroy', selectedMedia.value.id), {
            onSuccess: () => {
                useToast().add({ color: 'success', description: 'Folder deleted' })
                setSelectedMedia(null)
                if (isModalPage) {
                    modalRef.value?.reload()
                }
            }
        })
    } else {
        router.delete(route('admin.files.destroy', selectedMedia.value.id), {
            onSuccess: () => {
                useToast().add({ color: 'success', description: 'File deleted' })
                setSelectedMedia(null)
                if (isModalPage) {
                    modalRef.value?.reload()
                }
            }
        })
    }
}
</script>

<style scoped></style>