import { useElement } from '@modules/Builder/resources/scripts/use-element';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';

export const useCarousel = (element: VelnoxAIElement) => {
    const { className } = useElement(element);

    const items = computed(() => element.getProp('items'));

    function getStyles() {
        return `
            .${className.value} {
                .item {
                    flex-basis: ${100 / element.getProp('itemsInView')}%;
                }
                .container {
                    align-items: ${element.getProp('item.align')};
                }
                .img{
                    border-radius: ${element.getProp('item.borderRadius')}px;
                    width: ${element.getProp('item.width.unit') == 'auto' ? 'auto' : element.getProp('item.width.value') + element.getProp('item.width.unit')};
                    height: ${element.getProp('item.height.unit') == 'auto' ? 'auto' : element.getProp('item.height.value') + element.getProp('item.height.unit')};
                }
            }

            @container(max-width: 576px) {
                .${className.value} {
                    .item {
                        flex-basis: 100%;
                    }
                }
            }
        `;
    }

    return {
        getStyles,
        items,
    };
};
