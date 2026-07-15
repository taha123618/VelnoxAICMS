import AnimationSettings from '@modules/Builder/resources/components/settings/animation-settings.vue';
import BackgroundSettings from '@modules/Builder/resources/components/settings/background-settings.vue';
import BorderStyleSettings from '@modules/Builder/resources/components/settings/border-style-settings.vue';
import DecorationSettings from '@modules/Builder/resources/components/settings/decoration-settings.vue';
import DimensionSettings from '@modules/Builder/resources/components/settings/dimension-settings.vue';
import ElementInfoSettings from '@modules/Builder/resources/components/settings/element-info-settings.vue';
import PositionSettings from '@modules/Builder/resources/components/settings/position-settings.vue';
import SpacingSettings from '@modules/Builder/resources/components/settings/spacing-settings.vue';
import TransitionSettings from '@modules/Builder/resources/components/settings/transition-settings.vue';
import NavigationDropdownSettings from '@modules/Builder/resources/draggables/components/navigation/components/navigation-dropdown-settings.vue';
import NavigationLinkSettings from '@modules/Builder/resources/draggables/components/navigation/components/navigation-link-settings.vue';
import NavigationLogoSettings from '@modules/Builder/resources/draggables/components/navigation/components/navigation-logo-settings.vue';
import NavigationMobileMenuSettings from '@modules/Builder/resources/draggables/components/navigation/components/navigation-mobile-menu-settings.vue';
import NavigationSettings from '@modules/Builder/resources/draggables/components/navigation/components/navigation-settings.vue';
import Render from '@modules/Builder/resources/draggables/components/navigation/render.vue';
import FlexboxSettings from '@modules/Builder/resources/draggables/containers/flexbox/components/flexbox-settings.vue';
import Navigation from './navigation.vue';

export default {
    component: Navigation,
    renderable: Render,
    settings: [
        { name: 'Info', component: ElementInfoSettings },
        { name: 'Menu', component: NavigationSettings },
        { name: 'Logo', component: NavigationLogoSettings },
        { name: 'Nav links', component: NavigationLinkSettings },
        { name: 'Dropdown list', component: NavigationDropdownSettings },
        { name: 'Mobile menu', component: NavigationMobileMenuSettings },
        { name: 'Layout', component: FlexboxSettings },
        { name: 'Background', component: BackgroundSettings },
        { name: 'Sizing', component: DimensionSettings },
        { name: 'Spacing', component: SpacingSettings },
        { name: 'Border', component: BorderStyleSettings },
        { name: 'Position', component: PositionSettings },
        { name: 'Decoration', component: DecorationSettings },
        { name: 'Animation', component: AnimationSettings },
        { name: 'Transition', component: TransitionSettings },
    ],
};
