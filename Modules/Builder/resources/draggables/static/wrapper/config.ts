import { defaultDivStyles } from '@modules/Builder/resources/scripts/base-styles';
import { deepCopy } from '@modules/Builder/resources/scripts/utils';
import type { TElement } from '@modules/Builder/resources/scripts/types';

const config: TElement = {
    id: '__wrapper',
    name: 'Wrapper',
    canDrop: true,
    isLayoutElement: false,
    type: 'wrapper',
    icon: 'ph:square',
    props: {
        tag: 'div',
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
    children: [],
};

export default config;
