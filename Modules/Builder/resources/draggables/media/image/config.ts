import { defaultDivStyles } from '@modules/Builder/resources/scripts/base-styles';
import type { TElement } from '@modules/Builder/resources/scripts/types';
import { deepCopy } from '@modules/Builder/resources/scripts/utils';

const config: TElement = {
    id: 'image',
    category: 'media',
    canDrop: false,
    isLayoutElement: false,
    type: 'image',
    name: 'Image',
    icon: 'ph:image-fill',
    props: {
        tag: 'div',
        content: {
            src: 'https://placehold.co/400x200.png',
            objectFit: 'cover',
            linkType: 'none',
            alt: '',
            target: '_self',
            href: '',
        },
        styles: {
            custom: {
                ...deepCopy(defaultDivStyles.custom),
            },
            desktop: {
                default: {
                    ...deepCopy(defaultDivStyles.desktop),
                    display: 'block',
                    overflow: 'hidden',
                    padding: {
                        top: 0,
                        right: 0,
                        bottom: 0,
                        left: 0,
                        unit: 'px', // custom , px, rem, em
                        hasUnit: true,
                        isLinked: false,
                        isFourWay: true,
                    },
                    margin: {
                        top: 0,
                        right: 0,
                        bottom: 0,
                        left: 0,
                        unit: 'px', // custom , px, rem, em
                        hasUnit: true,
                        isLinked: false,
                        isFourWay: true,
                    },
                    height: {
                        value: 100,
                        unit: '%',
                    },
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

    children: [],
};

export default config;
