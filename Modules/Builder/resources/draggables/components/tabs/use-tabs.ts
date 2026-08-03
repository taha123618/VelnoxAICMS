import { useElement } from '@modules/Builder/resources/scripts/use-element';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';

export const useTabs = (element: VelnoxAIElement) => {
    const { className } = useElement(element);

    function getStyles() {
        return `
            .${className.value} {
                .trigger{
                    color: ${element.getProp('trigger.color')};
                    font-size: ${element.getProp('trigger.fontSize')}px;
                    font-family: ${element.getProp('trigger.fontFamily')};                    
                    font-weight: ${element.getProp('trigger.fontWeight')};
                }
                .trigger[data-state='active'] {
                    background: ${element.getProp('trigger.backgroundColor')};
                    color: ${element.getProp('trigger.activeColor')};
                }
                .indicator{
                    background-color: ${element.getProp('indicator.backgroundColor')};
                }
            }
        `;
    }

    return {
        getStyles,
    };
};
