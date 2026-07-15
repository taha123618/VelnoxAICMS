import { pickBy } from '@/helpers';
import { ITableData } from '@/types/types';

export const useDatatable = (
    filters: Record<string, any>,
    data: ITableData,
) => {
    const isLoading = ref(false);

    function getSortableHeader(
        column: any,
        buttonComponent: any,
        label: string,
    ) {
        const isSorted = column.getIsSorted();
        return h(buttonComponent, {
            color: 'neutral',
            variant: 'ghost',
            label: label,
            icon: isSorted
                ? isSorted === 'asc'
                    ? 'i-lucide-arrow-up-narrow-wide'
                    : 'i-lucide-arrow-down-wide-narrow'
                : 'i-lucide-arrow-up-down',
            class: '-mx-2.5',
            onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
        });
    }

    const form = reactive({
        search: filters.search,
        sort: filters.sortBy,
        // perPage: data.per_page,
        page: data.current_page,
    });

    const sortingOptions = computed(() => {
        if (form['sort'] && form['sort'] != undefined) {
            const isDescending = form['sort'].startsWith('-');
            return [
                {
                    id: isDescending ? form['sort'].slice(1) : form['sort'],
                    desc: isDescending,
                },
            ];
        } else {
            return [];
        }
    });

    function handleSort(sort: { id: string; desc: boolean }[]) {
        const sorting = sort[0];
        if (!sorting.id) {
            form['sort'] = '';
        } else {
            form['sort'] = sorting.desc ? `-${sorting.id}` : sorting.id;
        }
    }

    watchDebounced(
        () => form,
        () => {
            isLoading.value = true;
            router.reload({
                data: pickBy(form),
                onFinish: () => {
                    setTimeout(() => (isLoading.value = false), 500);
                },
            });
        },
        { debounce: 300, maxWait: 500, deep: true, immediate: false },
    );

    return {
        isLoading,
        form,
        handleSort,
        sortingOptions,
        getSortableHeader,
    };
};
