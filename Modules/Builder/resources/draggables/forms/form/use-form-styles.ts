import { useElement } from '@modules/Builder/resources/scripts/use-element';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';

export const useFormStyles = (element: ZioraElement) => {
    const { className } = useElement(element);

    function getStyles() {
        return `
            .${className.value} {
                .submit-button {
                    border-radius: ${element.getProp('button.borderRadius')}px;
                    font-weight: ${element.getProp('button.fontWeight')};
                    font-size: ${element.getProp('button.fontSize')}px;
                    color: ${element.getProp('button.color')};
                    border-color: ${element.getProp('button.borderColor')};
                    border-width: ${element.getProp('button.borderWidth')}px;
                    background-color: ${element.getProp('button.backgroundColor')};
                    padding: ${element.getProp('button.paddingY')}px ${element.getProp('button.paddingY')}px;
                    
                    &:hover{
                        background-color: ${element.getProp('button.hoverBackgroundColor')};
                        border-color: ${element.getProp('button.hoverBorderColor')};
                        color: ${element.getProp('button.hoverColor')};
                    }
                }

                .input-label{
                    font-size: ${element.getProp('label.fontSize')}px;
                    color: ${element.getProp('label.color')};
                    font-weight: ${element.getProp('label.fontWeight')};
                }
                .form-input,
                .select-input{
                    outline-style: solid;
                    background-color: #ffffff;
                    outline-width: ${element.getProp('input.borderWidth')}px;
                    outline-color: ${element.getProp('input.borderColor')};
                    padding: ${element.getProp('input.paddingY')}px ${element.getProp('input.paddingX')}px;
                    font-size: ${element.getProp('input.fontSize')}px;

                    &:focus-visible{
                        outline-width: ${element.getProp('input.focusBorderWidth')}px !important;
                        outline-color: ${element.getProp('input.focusBorderColor')} !important;
                    }
                }

                .radio-input{
                    height: ${element.getProp('checkbox.height')}px;
                    width: ${element.getProp('checkbox.width')}px;
                    background-color: #ffffff;
                }

                .checkbox-input{
                    background-color: #ffffff;
                    outline-style: solid;
                    outline-width: ${element.getProp('input.borderWidth')}px;
                    outline-color: ${element.getProp('input.borderColor')};
                    height: ${element.getProp('checkbox.height')}px;
                    width: ${element.getProp('checkbox.width')}px;
                }
                
                .checkbox-input-indicator{
                    background-color:  ${element.getProp('input.focusBorderColor')};
                }
                .checkbox-input-card{
                    outline-style: solid;
                    outline-width: ${element.getProp('input.borderWidth')}px;
                    outline-color: ${element.getProp('input.borderColor')}; 

                    &:has(*[data-state="checked"]){ 
                        outline-width: ${element.getProp('input.focusBorderWidth')}px;
                        outline-color: ${element.getProp('input.focusBorderColor')};
                        border-color: transparent;
                    }
                }
            }
        `;
    }
    return {
        getStyles,
    };
};
