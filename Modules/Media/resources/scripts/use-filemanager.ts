import { createSharedComposable } from '@vueuse/core';

const _useFilemanager = () => {
    const selectedMedia = ref<Record<string, any> | null>(null);

    const modalRef = ref<any>(null);
    const showModal = ref<boolean>(true);

    function setSelectedMedia(item: any = null) {
        selectedMedia.value = item;
    }

    return {
        modalRef,
        showModal,
        selectedMedia,
        setSelectedMedia,
    };
};

export const useFileManager = createSharedComposable(_useFilemanager);
