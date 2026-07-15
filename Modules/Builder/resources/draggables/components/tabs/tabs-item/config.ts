import { defaultDivStyles } from '@modules/Builder/resources/scripts/base-styles';
import { deepCopy } from '@modules/Builder/resources/scripts/utils';
import { TElement } from '@modules/Builder/resources/scripts/types';
const config: TElement = {
    id: 'tabs-item',
    canDrop: true,
    isSingleton: true,
    isLayoutElement: false,
    type: 'tabs-items',
    name: 'Tabs item',
    icon: 'ph:link-fill',
    children: [],
    props: {
        title: 'Item title',
        styles: {
            custom: {
                ...deepCopy(defaultDivStyles.custom),
            },
            desktop: {
                default: {
                    display: 'block',
                },
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
