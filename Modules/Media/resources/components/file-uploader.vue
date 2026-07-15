<template>
    <div>
        <div class="group relative rounded-lg bg-slate-800 transition-all duration-500">
            <label
                for="file"
                class="group flex h-full w-full cursor-pointer appearance-none flex-col items-center justify-center gap-1 rounded-lg border border-dashed border-gray-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 p-4 text-center transition hover:border-gray-400 dark:hover:border-neutral-500 focus:outline-none"
                :class="{
                    '!bg-primary-100 dark:!bg-primary-950/40 border-primary': filesBeingDragged,
                }"
                @dragover.prevent="filesBeingDragged = true"
                @dragleave.prevent="filesBeingDragged = false"
                @drop.prevent="
                    handleFileDrop($event);
                filesBeingDragged = false;
                "
            >
                <UIcon
                    name="ph:images"
                    class="text-primary-200 group-hover:text-primary-500 size-8 transition-all duration-500"
                />
                <span class="text-sm">
                    Drop images or
                    <span class="font-semibold text-primary-600 dark:text-primary-400 underline">
                        select
                    </span>
                    .
                </span>
                <input
                    type="file"
                    id="file"
                    class="sr-only"
                    multiple
                    @change="handleFileDrop($event)"
                    :accept="FILE_TYPES.toString()"
                />
            </label>
        </div>

        <ul
            v-if="hasFiles"
            class="max-h-48 mt-4 space-y-2.5 overflow-y-auto"
        >
            <li
                v-for="(progress, fileName) in uploadProgress"
                :key="fileName"
                class="animate-in zoom-in-95 duration-700"
            >
                <div class="flex items-center justify-between">
                    <span class="text-xs">
                        {{ fileName }}
                    </span>
                    <span class="text-xs"> {{ progress }}% </span>
                </div>
                <UProgress
                    class="flex-1"
                    :max="100"
                    color="success"
                    :model-value="progress"
                />
            </li>
        </ul>
    </div>
</template>

<script setup lang="ts">
import axios, { AxiosProgressEvent, AxiosResponse } from 'axios';

const props = defineProps<{
    onUploadComplete: () => void;
    currentFolder: string;
}>();

const toast = useToast()

const filesBeingDragged = ref(false);
const uploadProgress = ref({});
const chunkSize = 1 * 1024 * 1024; // 1MB
const FILE_TYPES = ['image/jpeg', 'image/png', 'image/gif'];
const MAX_FILE_SIZE = 5000000; // 5MB

const hasFiles = computed<boolean>(
    () => Object.keys(uploadProgress.value).length > 0,
);

async function handleFileDrop(event: DragEvent | Event) {
    let files: FileList | null = null; //: FileList | File[];
    if (event instanceof DragEvent) {
        files = event.dataTransfer!.files;
    } else if (event instanceof Event) {
        files = (<HTMLInputElement>event.target)!.files;
    }
    if (!files) {
        return;
    }
    const droppedFiles = Array.from(files);
    for (const file of droppedFiles) {
        if (!FILE_TYPES.includes(file.type)) {
            toast.add({
                title: 'Only PNG, JPG and GIF files are allowed.',
                color: 'error',
            });
            continue;
        }

        if (file.size > MAX_FILE_SIZE) {
            toast.add({
                title: 'Images must be maximum of 5MB in size.',
                color: 'error',
            });
            continue;
        }

        const totalChunks = Math.ceil(file.size / chunkSize);

        uploadProgress.value = { ...uploadProgress.value, [file.name]: 0 };

        for (let i = 0; i < totalChunks; i++) {
            const start = i * chunkSize;
            const end = start + chunkSize;
            const chunk = file.slice(start, end);

            const formData = new FormData();
            formData.append('file', chunk);
            formData.append('file_name', file.name);
            formData.append('chunk_index', i.toString());
            formData.append('total_chunks', totalChunks.toString());

            const response: AxiosResponse<any> = await axios.post(
                route('admin.files.store', [props.currentFolder]),
                formData,
                {
                    onUploadProgress: (progress: AxiosProgressEvent) => {
                        if (progress.total) {
                            const currentProgress = Math.round(
                                (progress.loaded / progress.total) * 100,
                            );
                            uploadProgress.value = {
                                ...uploadProgress.value,
                                [file.name]: currentProgress,
                            };
                        }
                    },
                },
            );

            if (response.data) {
                props.onUploadComplete();
            }
        }
    }
}


</script>

<style scoped></style>
