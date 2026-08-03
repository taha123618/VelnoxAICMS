import { transformOptions } from '@modules/Builder/resources/scripts/utils';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';

export const useFormInput = (element: VelnoxAIElement) => {

    const options = computed(() => {
        if (!element.getProp('options')) {
            return [];
        }
        return transformOptions(element.getProp('options'));
    });


    return {
        options
    };
};
