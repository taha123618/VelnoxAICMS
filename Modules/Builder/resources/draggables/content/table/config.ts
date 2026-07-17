import { defaultBackgroundStyles, defaultDivStyles } from '@modules/Builder/resources/scripts/base-styles';
import { deepCopy } from '@modules/Builder/resources/scripts/utils';
import type { TElement } from '@modules/Builder/resources/scripts/types';

const config: TElement = {
    id: 'table',
    category: 'content',
    type: 'table',
    name: 'Table',
    icon: 'ph:table-bold',
    canDrop: false,
    isLayoutElement: false,
    props: {
        content: { innerText: 'Header 1, Header 2, Header 3\nRow 1 Col 1, Row 1 Col 2, Row 1 Col 3\nRow 2 Col 1, Row 2 Col 2, Row 2 Col 3' },
        styles: {
            custom: deepCopy(defaultDivStyles.custom),
            desktop: {
                default: {
                    ...deepCopy(defaultDivStyles.desktop),
                    background: deepCopy(defaultBackgroundStyles.desktop),
                    width: { value: 100, unit: '%' }
                },
                hover: {}
            },
            tablet: { default: deepCopy(defaultDivStyles.mobile), hover: {} },
            mobile: { default: deepCopy(defaultDivStyles.mobile), hover: {} }
        }
    },
    children: []
};

export default config;
