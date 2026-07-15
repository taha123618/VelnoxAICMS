import { SharedData } from '@/types';

export const useNotification = () => {
    const flash = computed(() => usePage<SharedData>().props.flash);
    const toast = useToast();

    watch(
        flash,
        (value) => {
            if (!!value.error) {
                toast.add({
                    color: 'error',
                    description: value.error,
                });
            }

            if (!!value.success) {
                toast.add({
                    color: 'success',
                    description: value.success,
                });
            }
        },
        { deep: true },
    );
};
