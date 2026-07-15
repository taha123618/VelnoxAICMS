import { defaultBackgroundStyles, defaultDivStyles } from '@modules/Builder/resources/scripts/base-styles';
import { deepCopy } from '@modules/Builder/resources/scripts/utils';
import type { TElement } from '@modules/Builder/resources/scripts/types';

const config: TElement = {
    id: 'heading',
    category: 'typography',
    type: 'heading',
    name: 'Heading',
    icon: 'ph:text-h',
    canDrop: false,
    isLayoutElement: false,
    props: {
        tag: 'h1',
        content: { innerText: 'Add your heading text here' },
        styles: {
            custom: {
                ...deepCopy(defaultDivStyles.custom)
            },

            desktop: {
                default: {
                    ...deepCopy(defaultDivStyles.desktop),
                    background: deepCopy(defaultBackgroundStyles.desktop),
                    width: {
                        value: 100,
                        unit: 'auto',
                    },
                    minHeight: {
                        value: 0,
                        unit: 'px',
                    },
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
                    display: 'block',
                    color: '#000000',
                    fontSize: {
                        unit: 'px',
                        value: 30,
                    },
                    textAlign: 'left',
                    fontWeight: '600',
                    webkitTextStrokeWidth: 0,
                    webkitTextStrokeColor: 'transparent',
                    textDecoration: 'none',
                    textTransform: 'none',
                    fontStyle: 'normal', // normal, italic, oblique
                    fontVariant: 'normal', // small-caps, all-small-caps, petite-caps, all-petite-caps, unicase, titling-caps
                    fontFamily: 'Bebas Neue',
                    lineHeight: {
                        unit: 'em',
                        value: 1.3,
                    },
                    letterSpacing: 0,
                    textShadow: {
                        x: 0,
                        y: 0,
                        blur: 0,
                        color: 'transparent',
                    },
                },
                hover: {}
            },
            tablet: {
                default: {
                    ...deepCopy(defaultDivStyles.mobile),
                },
                hover: {}
            },
            mobile: {
                default: {
                    ...deepCopy(defaultDivStyles.mobile),
                },
                hover: {}
            },
        },
    },

    children: [],
};

export default config;
