import { defaultBackgroundStyles, defaultDivStyles } from '@modules/Builder/resources/scripts/base-styles';
import { deepCopy } from '@modules/Builder/resources/scripts/utils';
import type { TElement } from '@modules/Builder/resources/scripts/types';

const config: TElement = {
    id: 'quote',
    category: 'content',
    type: 'quote',
    name: 'Quote',
    icon: 'ph:quotes-bold',
    canDrop: false,
    isLayoutElement: false,
    props: {
        tag: 'blockquote',
        author: 'John Doe',
        content: { innerText: 'This is a beautiful quote element that you can customize in style.' },
        styles: {
            custom: deepCopy(defaultDivStyles.custom),
            desktop: {
                default: {
                    ...deepCopy(defaultDivStyles.desktop),
                    background: deepCopy(defaultBackgroundStyles.desktop),
                    width: { value: 100, unit: '%' },
                    padding: { top: 10, right: 0, bottom: 10, left: 20, unit: 'px', isFourWay: true, hasUnit: true },
                    borderWidth: { top: 0, right: 0, bottom: 0, left: 4, unit: 'px', isFourWay: true, hasUnit: true },
                    borderColor: { top: '#000000', right: '#000000', bottom: '#000000', left: '#3b82f6', hasUnit: false, isFourWay: true },
                    borderStyle: { top: 'none', right: 'none', bottom: 'none', left: 'solid', hasUnit: false, isFourWay: true },
                    fontStyle: 'italic',
                    fontSize: { value: 18, unit: 'px' }
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
