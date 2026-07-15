import AnimationSettings from '@modules/Builder/resources/components/settings/animation-settings.vue';
import BackgroundSettings from '@modules/Builder/resources/components/settings/background-settings.vue';
import BorderStyleSettings from '@modules/Builder/resources/components/settings/border-style-settings.vue';
import DecorationSettings from '@modules/Builder/resources/components/settings/decoration-settings.vue';
import DimensionSettings from '@modules/Builder/resources/components/settings/dimension-settings.vue';
import ElementInfoSettings from '@modules/Builder/resources/components/settings/element-info-settings.vue';
import PositionSettings from '@modules/Builder/resources/components/settings/position-settings.vue';
import SpacingSettings from '@modules/Builder/resources/components/settings/spacing-settings.vue';
import TransitionSettings from '@modules/Builder/resources/components/settings/transition-settings.vue';
import FormButtonSettings from '@modules/Builder/resources/draggables/forms/form/components/form-button-settings.vue';
import FormInputStyles from '@modules/Builder/resources/draggables/forms/form/components/form-input-styles.vue';
import FormLabelStyles from '@modules/Builder/resources/draggables/forms/form/components/form-label-styles.vue';
import FormSettings from '@modules/Builder/resources/draggables/forms/form/components/form-settings.vue';
import Form from '@modules/Builder/resources/draggables/forms/form/form.vue';
import Render from '@modules/Builder/resources/draggables/forms/form/render.vue';

export default {
    component: Form,
    renderable: Render,
    settings: [
        { name: 'Info', component: ElementInfoSettings },
        { name: 'Form settings', component: FormSettings },
        { name: 'Button settings', component: FormButtonSettings },
        { name: 'Label styles', component: FormLabelStyles },
        { name: 'Input styles', component: FormInputStyles },
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
