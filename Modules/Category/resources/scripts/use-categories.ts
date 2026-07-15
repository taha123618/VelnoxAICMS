import axios from 'axios';

export const useCategories = () => {
    const categories = ref<Modules.Category.Data.CategoryData[]>([]);

    watchEffect(() => {
        axios
            .get(
                route('api.categories.index', {
                    perPage: 100,
                    orderBy: 'name',
                    orderDir: 'asc',
                }),
            )
            .then((response) => {
                categories.value = response.data;
            })
            .catch((error) => {
                console.log(error);
            });
    });

    return {
        categories,
    };
};
