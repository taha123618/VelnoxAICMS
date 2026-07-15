import {
    defaultBackgroundStyles,
    defaultDivStyles,
} from '@modules/Builder/resources/scripts/base-styles';
import { deepCopy } from '@modules/Builder/resources/scripts/utils';
import { TElement } from '@modules/Builder/resources/scripts/types';

const config: TElement = {
    id: 'testimonials',
    category: 'components',
    canDrop: false,
    isLayoutElement: false,
    type: 'testimonials',
    name: 'Testimonials',
    icon: 'ph:quotes',
    props: {
        autoplay: false,
        duration: 5000,
        arrows: true,
        dots: true,
        loop: false,
        autoScroll: false,
        variant: 'vertical', // 'horizontal', 'vertical'
        avatar: {
            height: {
                value: 75,
                unit: 'px',
            },
            width: {
                value: 75,
                unit: 'px',
            },
        },
        image: {
            height: {
                value: 100,
                unit: '%',
            },
            width: {
                value: 100,
                unit: '%',
            },
            borderRadius: 10,
        },
        heading: {
            fontFamily: 'Inter',
            fontWeight: 700,
            fontSize: 24,
            color: '#000000',
        },
        subHeading: {
            fontFamily: 'Inter',
            fontWeight: 300,
            fontSize: 12,
            color: '#6a7282',
        },
        body: {
            fontFamily: 'Inter',
            fontWeight: 400,
            fontSize: 18,
            color: '#6a7282',
        },
        item: {
            align: 'center', //
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

        styles: {
            custom: {
                ...deepCopy(defaultDivStyles.custom),
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
