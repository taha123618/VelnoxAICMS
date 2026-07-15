import { defaultDivStyles } from "@modules/Builder/resources/scripts/base-styles";
import { TElement } from "@modules/Builder/resources/scripts/types";
import { deepCopy } from "@modules/Builder/resources/scripts/utils";

const config: TElement = {
    id: 'accordion-item',
    group: 'basic',
    canDrop: true,
    isSingleton: true,
    isLayoutElement: false,
    type: 'accordion-item',
    name: 'Accordion item',
    icon: 'ph:link-fill',
    children: [],
    props: {
        title: 'Item title',
        styles: {
            custom: {
                ...deepCopy(defaultDivStyles.custom),
            },
            desktop: {
                default: {},
                hover: {},
            },
            tablet: {
                default: {},
                hover: {},
            },
            mobile: {
                default: {},
                hover: {},
            },
        },
    },
};

export default config;
