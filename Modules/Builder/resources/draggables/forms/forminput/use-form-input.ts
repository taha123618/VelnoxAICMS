import { transformOptions } from '@modules/Builder/resources/scripts/utils';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';

export const useFormInput = (element: ZioraElement) => {

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
