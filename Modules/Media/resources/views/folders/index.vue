<template>
    <HeadlessModal v-if="isModalPage" ref="modalRef" v-slot="{ close }"
        :after-leave="setSelectedMedia(null)">
        <UModal v-model:open="showModal" :ui="{
            content: 'max-w-7xl',
            body: 'p-0 sm:p-0',
            title: 'font-bold text-xl',
        }" :dismissible="false" description="Select and insert a file" title="File manager">
            <template #body>
                <FileManager :is-modal-page="isModalPage" :folders="folders" :content="content"
                    :breadcrumbs="breadcrumbs" :current-folder="currentFolder" :closeModal="close" />
            </template>
        </UModal>
    </HeadlessModal>

    <AdminLayout v-else :breadcrumbs="Breadcrumbs">
        <Head title="File manager" />
        <FileManager :is-modal-page="false" :folders="folders" :content="content" :breadcrumbs="breadcrumbs"
            :current-folder="currentFolder" />
    </AdminLayout>
</template>

<script setup lang="ts">
import AdminLayout from '@modules/Dashboard/resources/layouts/AdminLayout.vue';
import type { BreadcrumbItem } from '@nuxt/ui'
import FileManager from '@modules/Media/resources/components/file-manager.vue';
import { useFileManager } from '@modules/Media/resources/scripts/use-filemanager';
import { HeadlessModal } from '@inertiaui/modal-vue'

const Breadcrumbs: BreadcrumbItem[] = [
    {
        label: 'File manager',
        icon: 'ph:image',
        href: route('admin.folders.index'),
    },
];

const { folders, content, isModalPage, breadcrumbs, currentFolder } = defineProps<{
    isModalPage: boolean;
    folders: Record<string, any>[];
    content: Record<string, any>[];
    breadcrumbs: Record<string, any>[];
    currentFolder: string;
}>();


const { setSelectedMedia, modalRef, showModal } = useFileManager();


onBeforeMount(() => {
    showModal.value = true
})
</script>

<style scoped></style>
