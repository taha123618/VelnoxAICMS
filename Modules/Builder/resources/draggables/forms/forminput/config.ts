import {
    defaultBackgroundStyles,
    defaultDivStyles,
} from '@modules/Builder/resources/scripts/base-styles';
import { InputTypes } from '@modules/Builder/resources/scripts/enums';
import type { TElement } from '@modules/Builder/resources/scripts/types';
import { deepCopy } from '@modules/Builder/resources/scripts/utils';

const config: TElement = {
    id: 'forminput',
    category: 'forms',
    canDrop: false,
    isLayoutElement: false,
    type: 'forminput',
    name: 'Form input',
    icon: 'ph:textbox',
    props: {
        tag: 'div',
        type: InputTypes.Text,
        name: 'name',
        label: {
            label: 'Label',
            labelPosition: 'top'
        },
        placeholder: 'Enter text...',
        orientation: 'horizontal',
        options: 'Option one | one \n Option two | two',
        radioOrCheckboxGroupVariant: 'list', // list | table | card
        checkboxVariant: 'list', // list | card
        defaultValue: '',
        numberMin: 0,
        numberMax: 100,
        numberStep: 0.5,
        
        styles: {
            desktop: {
                default: {
                    ...deepCopy(defaultDivStyles.desktop),
                    background: deepCopy(defaultBackgroundStyles.desktop),
                    display: 'table-cell',
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
