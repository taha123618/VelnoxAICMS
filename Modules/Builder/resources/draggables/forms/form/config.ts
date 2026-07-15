import {
    defaultBackgroundStyles,
    defaultDivStyles,
} from '@modules/Builder/resources/scripts/base-styles';
import type { TElement } from '@modules/Builder/resources/scripts/types';
import { deepCopy } from '@modules/Builder/resources/scripts/utils';

const config: TElement = {
    id: 'form',
    category: 'forms',
    canDrop: true,
    isLayoutElement: false,
    type: 'form',
    name: 'Form',
    icon: 'ph:table',
    props: {
        tag: 'div',
        endpoint: '',
        afterSubmit: 'flash', // flash | redirect
        successMessage: 'Thank you for your message. We will be in touch shortly.',
        redirectTo: '',
        button:{
            align: 'right',
            label: 'Submit',
            borderRadius: 5,
            fontSize: 14,
            fontWeight: 400,
            color: '#ffffff',
            hoverColor: '#ffffff',
            backgroundColor: '#605eff',
            hoverBackgroundColor: '#7978ff',
            borderWidth: 1,
            borderColor: '#605eff',
            hoverBorderColor: '#7978ff',
            paddingX: 20,
            paddingY: 16,
        },
        input:{
            paddingY: 10,
            paddingX: 10,
            fontSize: 14,
            borderWidth: 1,
            borderColor: '#cecece',
            focusBorderColor: '#605eff',
            focusBorderWidth: 2,
        },
        checkbox:{
            height: 16,
            width: 16,
        },
        label: {
            label: 'Label',
            fontSize: 14,
            fontWeight: 500,
            color: '#314158'
        },
        

        styles: {
            desktop: {
                default: {
                    ...deepCopy(defaultDivStyles.desktop),
                    background: deepCopy(defaultBackgroundStyles.desktop),
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
