<template>
    <div class="flex w-full divide-x divide-neutral-200 dark:divide-neutral-800 border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900">
        <div class="w-56 ">
            <div class="px-3 pt-3">
                <h2 class="font-bold">Folders</h2>
            </div>
            <FolderTree :is-modal-page="isModalPage" :items="folders" />
        </div>
        <div class="flex-1">
            <UCard :ui="{
                root: 'border-0 ring-0',
                header: 'p-0 sm:p-0',
                body: 'p-0 sm:p-0',
            }">
                <template #header>
                    <div class="flex p-1.5 border-b border-neutral-200 dark:border-neutral-800 justify-between items-center">
                        <div class="flex items-center gap-2">
                            <UButton size="xs" variant="solid" icon="ph:plus-circle" label="New folder"
                                :disabled="!authUser.can.create_folders" @click="handleFolderCreateClick()">
                            </UButton>
                            <UModal v-model:open="showUploadModal" :dismissible="false" title="Upload file" :close="{
                                color: 'primary',
                                variant: 'soft',
                                class: 'rounded-full'
                            }" :ui="{
                                content: 'top-6 left-1/2 -translate-x-1/2 -translate-y-0'
                            }">
                                <UButton size="xs" icon="ph:upload" label="Upload file"
                                    :disabled="!authUser.can.create_folders" />
                                <template #body>
                                    <FileUploader :current-folder="currentFolder"
                                        :onUploadComplete="handleUploadComplete" />
                                </template>
                            </UModal>
                        </div>

                        <div>
                        </div>
                    </div>
                    <div class="p-1.5 text-sm flex flex-wrap items-center">
                        <UBreadcrumb :items="breadcrumbs">
                            <template #item-label="{ item, active }">
                                <BreadcrumbLinkItem :item="item" :is-active="active" @clicked="!active
                                    ? handleFolderDoubleClick(item.id)
                                    : null
                                    " />
                            </template>
                            <template #separator>
                                <span class="mx-2 text-neutral-400">
                                    /
                                </span>
                            </template>
                        </UBreadcrumb>
                    </div>
                </template>
                <div :class="isModalPage ? 'h-[60svh]' : 'h-[75svh]'" class="p-1.5  overflow-y-auto">
                    <div class="flex flex-wrap gap-4 overflow-y-auto p-2">
                        <template v-for="item in content" :key="item.id">
                            <ItemFolder v-if="item.isDirectory" :item="item" :is-modal-page="isModalPage"
                                @clicked="setSelectedMedia(item)" @double-clicked="handleFolderDoubleClick(item.id)" />

                            <ItemFile v-else :is-modal-page="isModalPage" :item="item" />
                        </template>
                    </div>
                </div>
            </UCard>
        </div>

        <div class="w-56 p-3">
            <ItemDetails :is-modal-page="isModalPage" :content="content" :currentFolder="currentFolder" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { visitModal } from '@inertiaui/modal-vue'
import { router } from '@inertiajs/vue3';
import ItemFolder from '@modules/Media/resources/components/item-folder.vue';
import ItemFile from '@modules/Media/resources/components/item-file.vue';
import { combine } from '@atlaskit/pragmatic-drag-and-drop/combine';
import { monitorForElements } from '@atlaskit/pragmatic-drag-and-drop/element/adapter';
import FileUploader from '@modules/Media/resources/components/file-uploader.vue';
import BreadcrumbLinkItem from '@modules/Media/resources/components/breadcrumb-item.vue';
import FolderTree from '@modules/Media/resources/components/folder-tree.vue';
import ItemDetails from '@modules/Media/resources/components/item-details.vue';
import { useAuth } from '@modules/Auth/resources/composables/use-auth';
import { useFileManager } from '@modules/Media/resources/scripts/use-filemanager';

const { folders, content, isModalPage, breadcrumbs, currentFolder } = defineProps<{
    isModalPage: boolean;
    folders: Record<string, any>[];
    content: Record<string, any>[];
    breadcrumbs: Record<string, any>[];
    currentFolder: string;
}>();

const showUploadModal = ref<boolean>(false)
const authUser = useAuth()


const { setSelectedMedia, modalRef } = useFileManager()

function handleUploadComplete() {
    if (isModalPage) {
        modalRef.value?.reload({ only: ['content'], data: { folder: currentFolder } })
    } else {
        router.reload({ only: ['content'], data: { folder: currentFolder } })
    }
}

function handleFolderDoubleClick(id: string) {
    if (isModalPage) {
        modalRef.value?.reload({ data: { folder: id } })
    } else {
        router.reload({ data: { folder: id } })
    }
}


function handleFolderCreateClick() {
    visitModal(route('admin.folders.create', { parent: currentFolder }), {
        onClose: () => {
            if (isModalPage) {
                modalRef.value?.reload()
            }
        },
    })
}

watchEffect((onCleanup) => {
    const dndFunction = combine(
        monitorForElements({
            canMonitor({ source }) {
                return source.data?.draggableType !== 'TREE';
            },
            onDrop({ source, location }) {
                const target = location.current.dropTargets[0];
                if (!target) {
                    return;
                }
                const destinationId = target.data.destinationId;
                const sourceData = source.data.item as Record<string, any> | undefined;
                if (sourceData && destinationId) {
                    router.put(route('admin.media.drag'), {
                        destinationId: `${target.data.destinationId}`,
                        sourceId: `${sourceData!.id}`,
                        type: sourceData.isDirectory ? 'folder' : 'file'
                    })

                    if (isModalPage) {
                        modalRef.value?.reload({ data: { folder: currentFolder } })
                    }
                }
            }
        })
    )

    onCleanup(() => {
        dndFunction();
    });
})
</script>

<style scoped></style>