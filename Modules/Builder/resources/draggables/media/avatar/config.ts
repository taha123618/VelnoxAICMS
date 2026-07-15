import {
    defaultBackgroundStyles,
    defaultDivStyles,
} from '@modules/Builder/resources/scripts/base-styles';
import type { TElement } from '@modules/Builder/resources/scripts/types';
import { deepCopy } from '@modules/Builder/resources/scripts/utils';
const config: TElement = {
    id: 'avatar',
    category: 'media',
    canDrop: true,
    isLayoutElement: false,
    type: 'avatar',
    name: 'Avatar',
    icon: 'ph:user-circle',
    props: {
        src: '',
        alt: '',
        text: '',
        styles: {
            desktop: {
                default: {
                    ...deepCopy(defaultDivStyles.desktop),
                    background: deepCopy(defaultBackgroundStyles.desktop),
                    overflow: 'hidden',
                    width: {
                        value: 100,
                        unit: 'px',
                    },
                    height: {
                        value: 100,
                        unit: 'px',
                    },
                    borderRadius: {
                        top: 100,
                        right: 100,
                        bottom: 100,
                        left: 100,
                        unit: '%',
                        isFourWay: true,
                        isLinked: false,
                        hasUnit: true,
                    },
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
            custom: {
                ...deepCopy(defaultDivStyles.custom),
            },
        },
    },
    children: [],
};

export default config;
