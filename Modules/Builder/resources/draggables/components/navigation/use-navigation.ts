import { useElement } from '@modules/Builder/resources/scripts/use-element';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';

export const useNavigation = (element: VelnoxAIElement) => {
    const showMobileMenu = ref(false);
    const { className } = useElement(element);

    function getStyles() {
        return `
            .${className.value} {
                .menu_list_item{
                    text-transform: ${element.getProp('menuListLink.textTransform')};
                    font-weight: ${element.getProp('menuListLink.fontWeight')};
                    font-size: ${element.getProp('menuListLink.fontSize')}px;
                    font-family: ${element.getProp('menuListLink.fontFamily')};
                    color: ${element.getProp('menuListLink.color')};
                    padding:  ${element.getProp('menuListLink.paddingY')}px ${element.getProp('menuListLink.paddingX')}px;

              
                    &:hover,
                    &[data-state="open"]{
                        background-color: ${element.getProp('menuListLink.hoverBackgroundColor')};
                        color: ${element.getProp('menuListLink.hoverColor')};
                    }    
                }
                .menu_list_dropdown {
                    text-transform: ${element.getProp('dropdown.textTransform')};
                    background-color: ${element.getProp('dropdown.backgroundColor')};
                    border-radius: ${element.getProp('dropdown.borderRadius') || 0}px;
                    border-width: ${element.getProp('dropdown.borderWidth') || 0}px;
                    border-style: ${element.getProp('dropdown.borderStyle')};
                    border-color: ${element.getProp('dropdown.borderColor')};
                }

                .dropdown_arrow{
                    border: ${element.getProp('dropdown.borderColor')};
                    background: ${element.getProp('dropdown.backgroundColor')};
                }

                .menu_list_dropdown_item {
                    color: ${element.getProp('dropdown.color')};
                    p{
                        font-weight: ${element.getProp('dropdown.fontWeight')};
                        font-size: ${element.getProp('dropdown.fontSize')}px;
                    }
                }

                .menu_list_dropdown_item:hover {
                    color: ${element.getProp('dropdown.hoverColor')};
                    background: ${element.getProp('dropdown.hoverBackgroundColor')};
                }

                .logo{
                    width: ${element.getProp('logo.width.unit') == 'auto' ? 'auto' : element.getProp('logo.width.value')! + element.getProp('logo.width.unit')};
                    height: ${element.getProp('logo.height.unit') == 'auto' ? 'auto' : element.getProp('logo.height.value')! + element.getProp('logo.height.unit')};
                }

                .toggle_button{
                    height: ${element.getProp('toggleButton.height')}px;
                    background: ${element.getProp('toggleButton.backgroundColor')};
                    font-size: ${element.getProp('toggleButton.fontSize')}px;
                    color: ${element.getProp('toggleButton.color')};
                    padding:  ${element.getProp('toggleButton.paddingY')}px ${element.getProp('toggleButton.paddingX')}px;
                    border-radius: ${element.getProp('toggleButton.borderRadius') || 0}px;
                }

            }
                
            .${className.value}_mobile_menu{
                background: ${element.getProp('mobileMenu.backgroundColor')};
            }

            .${className.value}_mobile_menu_link{
                color: ${element.getProp('mobileMenu.color')};
                font-size: ${element.getProp('mobileMenu.fontSize')}px;
            }
           
        `;
    }

    const mobileMenuSide = computed<'left' | 'right'>(() => {
        if (!element.getProp('mobileMenu.side')) {
            return 'left';
        }
        return String(element.getProp('mobileMenu.side')) as 'left' | 'right';
    });

    const mobileMenuCloseButtonColor = computed(
        () =>
            element.getProp('mobileMenu.closeButtonColor') as
            | 'error'
            | 'primary'
            | 'secondary'
            | 'success'
            | 'info'
            | 'warning'
            | 'neutral'
            | undefined,
    );
    const mobileMenuCloseButtonVariant = computed(
        () =>
            element.getProp('mobileMenu.closeButtonVariant') as
            | 'link'
            | 'solid'
            | 'outline'
            | 'soft'
            | 'subtle'
            | 'ghost'
            | undefined,
    );
    const mobileMenuCloseButtonSize = computed(
        () =>
            element.getProp('mobileMenu.closeButtonSize') as
            | 'sm'
            | 'md'
            | 'xs'
            | 'lg'
            | 'xl'
            | undefined,
    );

    return {
        showMobileMenu,
        getStyles,
        mobileMenuCloseButtonSize,
        mobileMenuCloseButtonVariant,
        mobileMenuCloseButtonColor,
        mobileMenuSide,
    };
};
