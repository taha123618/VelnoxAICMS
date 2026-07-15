import {
    defaultBackgroundStyles,
    defaultDivStyles,
} from '@modules/Builder/resources/scripts/base-styles';
import { deepCopy } from '@modules/Builder/resources/scripts/utils';
import { TElement } from '@modules/Builder/resources/scripts/types';

const config: TElement = {
    id: 'image-carousel',
    category: 'media',
    canDrop: false,
    isLayoutElement: false,
    type: 'image-carousel',
    name: 'Image carousel',
    icon: 'ph:slideshow',
    props: {
        items: [],
        item: {
            align: 'center',
            height: {
                value: 100,
                unit: 'auto',
            },
            width: {
                value: 100,
                unit: 'auto',
            },
            borderRadius: 0,
        },
        itemsInView: 3, // converted to flex-basis: 33.33%
        orientation: 'horizontal',
        fade: false,
        arrows: true,
        dots: true,
        loop: false,
        autoScroll: false,
        autoplay: {
            active: false,
            delay: 2000,
        },

        styles: {
            custom: {
                ...deepCopy(defaultDivStyles.custom)
            },
            desktop: {
                default: {
                    ...deepCopy(defaultDivStyles.desktop),
                    background: {
                        ...deepCopy(defaultBackgroundStyles.desktop),
                    },
                    height: {
                        value: 100,
                        unit: 'auto',
                    },
                    margin: {
                        top: 'auto',
                        right: 'auto',
                        bottom: 'auto',
                        left: 'auto',
                        unit: 'custom',
                        isFourWay: true,
                        isLinked: false,
                        hasUnit: true,
                    },
                    padding: {
                        top: 0,
                        right: 0,
                        bottom: 0,
                        left: 0,
                        unit: 'rem',
                        isFourWay: true,
                        isLinked: false,
                        hasUnit: true,
                    },
                },
                hover: {}
            },
            tablet: {
                default: {},
                hover: {}
            },
            mobile: {
                default: {},
                hover: {}
            },
        },
    },
    children: [],
};

export default config;
