import {
    defaultBackgroundStyles,
    defaultDivStyles,
} from '@modules/Builder/resources/scripts/base-styles';
import { deepCopy } from '@modules/Builder/resources/scripts/utils';
import type { TElement } from '@modules/Builder/resources/scripts/types';

const config: TElement = {
    id: 'link',
    category: 'typography',
    canDrop: false,
    isLayoutElement: false,
    type: 'link',
    name: 'Link',
    icon: 'ph:link-fill',

    props: {
        tag: 'a',
        variant: 'link', // 'btn', '
        content: {
            innerText: 'My link',
            href: '#',
            target: '_self',
            linkType: 'external',
        },
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
                    display: 'inline',
                    color: '#000000',
                    fontSize: {
                        unit: 'px',
                        value: 14,
                    },
                    textAlign: 'left',
                    fontWeight: '400',
                    webkitTextStrokeWidth: 0,
                    webkitTextStrokeColor: 'transparent',
                    textDecoration: 'none',
                    textTransform: 'none',
                    fontStyle: 'normal', // normal, italic, oblique
                    fontVariant: 'normal', // small-caps, all-small-caps, petite-caps, all-petite-caps, unicase, titling-caps
                    fontFamily: 'Inter',
                    lineHeight: {
                        unit: 'em',
                        value: 1.5,
                    },
                    letterSpacing: 0,
                    textShadow: {
                        x: 0,
                        y: 0,
                        blur: 0,
                        color: 'transparent',
                    },
                },
                hover: {},
                active: {},
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
