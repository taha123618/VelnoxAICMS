import {
    defaultBackgroundStyles,
    defaultDivStyles,
} from '@modules/Builder/resources/scripts/base-styles';
import { deepCopy } from '@modules/Builder/resources/scripts/utils';
import type { TElement } from '@modules/Builder/resources/scripts/types';

const config: TElement = {
    category: 'typography',
    id: 'paragraph',
    canDrop: false,
    isLayoutElement: false,
    type: 'paragraph',
    name: 'Paragraph',
    icon: 'ph:article-ny-times',
    props: {
        tag: 'p',
        content: {
            innerText:
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.',
        },
        styles: {
            custom: {
                ...deepCopy(defaultDivStyles.custom)
            },
            desktop: {
                default: {
                    ...deepCopy(defaultDivStyles.desktop),
                    background: deepCopy(defaultBackgroundStyles.desktop),
                    padding: {
                        top: 0,
                        right: 0,
                        bottom: 0,
                        left: 0,
                        unit: 'px',
                        isFourWay: true,
                        isLinked: false,
                        hasUnit: true,
                    },
                    display: 'block'
                },
                hover: {},
            },
            tablet: {
                default: {
                    ...deepCopy(defaultDivStyles.mobile),
                },
                hover: {},
            },
            mobile: {
                default: {
                    ...deepCopy(defaultDivStyles.mobile),
                },
                hover: {},
            },
        },
    },

    children: [],
};

export default config;
