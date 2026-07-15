<template>
    <UContextMenu
        :items="items"
        :ui="{
            content: 'w-48',
        }"
    >
        <slot />

        <BaseConfirmationModal
            v-model="isRevealed"
            @close="cancel"
            @cancel="cancel"
            @confirm="confirm"
        />
    </UContextMenu>
</template>

<script setup lang="ts">

import { router } from '@inertiajs/vue3';
import type { ContextMenuItem } from '@nuxt/ui';
import { useConfirmDialog } from '@vueuse/core';
import { visitModal } from '@inertiaui/modal-vue'
import { useFileManager } from '@modules/Media/resources/scripts/use-filemanager';

const { item, isModalPage } = defineProps<{
    item: Record<string, any>;
    isModalPage: boolean,
}>();

const content = computed<any>(() => usePage().props.content || [])

const { selectedMedia, setSelectedMedia, modalRef } = useFileManager()


const { isRevealed, cancel, confirm, reveal } = useConfirmDialog();

function handleModalReload(params: any = null) {
    modalRef.value?.reload(params)
}

const items = computed<ContextMenuItem[][]>(() => [
    [
        {
            label: 'Open',
            icon: 'ph:folder-open',
            disabled: !item.isDirectory,
            onSelect: () => {
                if (isModalPage) {
                    handleModalReload({ data: { folder: item.id } })
                } else {
                    router.reload({ data: { folder: item.id } })
                }
            },
        },
        {
            label: 'Rename',
            icon: 'ph:note-pencil',
            disabled: !item.isDirectory || !item.can.update,
            class: (!item.isDirectory || !item.can.update) && 'hidden',
            onSelect: () => {
                visitModal(route('admin.folders.edit', item.id), {
                    onClose: () => {
                        if (selectedMedia.value) {
                            setSelectedMedia(content.value!.find((c: Record<string, any>) => c.id == selectedMedia.value!.id) || null)
                        }
                        if (isModalPage) {
                            handleModalReload()
                        }
                    }
                })
            }
        },
        {
            label: 'Delete',
            icon: 'ph:trash',
            color: 'error',
            disabled: !item.can.delete,
            class: !item.can.delete && 'hidden',
            onSelect: handleDelete
        },
    ],
]);

async function handleDelete() {
    const { isCanceled } = await reveal();

    if (isCanceled) {
        return;
    }
    if (item.isDirectory) {
        router.delete(route('admin.folders.destroy', item.id), {
            onSuccess: () => {
                useToast().add({ color: 'success', description: 'Folder deleted' })
                if (selectedMedia.value && item.id == selectedMedia.value?.id) {
                    setSelectedMedia(null)
                }
                if (isModalPage) {
                    handleModalReload()
                }
            }
        })
    } else {
        router.delete(route('admin.files.destroy', item.id), {
            onSuccess: () => {
                useToast().add({ color: 'success', description: 'File deleted' })
                if (selectedMedia.value && item.id == selectedMedia.value?.id) {
                    setSelectedMedia()
                }
                if (isModalPage) {
                    handleModalReload()
                }
            }
        })
    }
}
</script>

<style scoped></style>
