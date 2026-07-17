import AnimationSettings from '@modules/Builder/resources/components/settings/animation-settings.vue';
import BackgroundSettings from '@modules/Builder/resources/components/settings/background-settings.vue';
import BorderStyleSettings from '@modules/Builder/resources/components/settings/border-style-settings.vue';
import CustomStyleSettings from '@modules/Builder/resources/components/settings/custom-style-settings.vue';
import DimensionSettings from '@modules/Builder/resources/components/settings/dimension-settings.vue';
import ElementInfoSettings from '@modules/Builder/resources/components/settings/element-info-settings.vue';
import SpacingSettings from '@modules/Builder/resources/components/settings/spacing-settings.vue';
import GenericFormsSettings from '@modules/Builder/resources/components/settings/generic-forms-settings.vue';
import Component from '../../generic/generic-forms.vue';
import Render from '../../generic/generic-forms-render.vue';

export default {
    component: Component,
    renderable: Render,
    settings: [
        { name: 'Info', component: ElementInfoSettings },
        { name: 'Form Switch Options', component: GenericFormsSettings },
        { name: 'Background', component: BackgroundSettings },
        { name: 'Border', component: BorderStyleSettings },
        { name: 'Size', component: DimensionSettings },
        { name: 'Spacing', component: SpacingSettings },
        { name: 'Custom styling', component: CustomStyleSettings },
        { name: 'Animations', component: AnimationSettings },
    ]
};
