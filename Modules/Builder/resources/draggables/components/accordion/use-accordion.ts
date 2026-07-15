import { useElement } from '@modules/Builder/resources/scripts/use-element';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import type { AccordionItem as TAccordionItem } from '@nuxt/ui';

export const useAccordion = (element: ZioraElement) => {
    const { className } = useElement(element);

    const items = computed<TAccordionItem[]>(
        () => element.children as TAccordionItem[],
    );

    function getStyles() {
        return `
            .${className.value} {
                .trigger{
                    background: white;
                    color: #252424;
                    padding-top: 10px;
                    padding-bottom: 10px;
                    padding-left: 10px;
                    padding-right: 10px;
                    fontSize: 17px;
                    fontFamily: 'Arial'
                }
            }
        `;
    }

    return {
        getStyles,
        items
    };
};
