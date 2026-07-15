import { defaultBackgroundStyles, defaultDivStyles } from '@modules/Builder/resources/scripts/base-styles';
import { deepCopy } from '@modules/Builder/resources/scripts/utils';
import type { TElement } from '@modules/Builder/resources/scripts/types';

const config: TElement = {
    id: 'grid',
    category: 'containers',
    canDrop: true,
    isLayoutElement: false,
    type: 'grid',
    name: 'Grid',
    icon: 'ph:grid-four-fill',

    props: {
        tag: 'div',
        styles: {
            desktop: {
                default: {
                    ...deepCopy(defaultDivStyles.desktop),
                    background: deepCopy(defaultBackgroundStyles.desktop),
                    display: 'grid',
                    gridAutoFlow: 'row',
                    gridTemplateColumns: {
                        unit: 'fr', // 'fr' or 'custom'
                        value: 2,
                    },
                    gridTemplateRows: {
                        unit: 'fr', // 'fr' or 'custom'
                        value: 1,
                    },
                    columnGap: 0,
                    rowGap: 0,
                    justifyItems: 'start',
                    alignItems: 'start',
                },
                hover: {
                    display: 'grid',
                }
            },
            tablet: {
                default: {
                    ...deepCopy(defaultDivStyles.mobile),
                    gridAutoFlow: 'row',
                    gridTemplateColumns: {
                        unit: 'fr', // 'fr' or 'custom'
                        value: 1,
                    },
                    justifyItems: 'start',
                    alignItems: 'start',
                },
                hover: {
                    display: 'grid',
                }
            },
            mobile: {
                default: {
                    ...deepCopy(defaultDivStyles.mobile),
                    gridAutoFlow: 'row',
                    gridTemplateColumns: {
                        unit: 'fr', // 'fr' or 'custom'
                        value: 1,
                    },
                    justifyItems: 'start',
                    alignItems: 'start',
                },
                hover: {
                    display: 'grid',
                }
            },

            custom: {
                ...deepCopy(defaultDivStyles.custom)
            },
        },
    },
    children: [],
};

export default config;
