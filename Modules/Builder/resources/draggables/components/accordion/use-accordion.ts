import { useElement } from '@modules/Builder/resources/scripts/use-element';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';
import type { AccordionItem as TAccordionItem } from '@nuxt/ui';

export const useAccordion = (element: VelnoxAIElement) => {
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
