import AnimationSettings from '@modules/Builder/resources/components/settings/animation-settings.vue';
import BackgroundSettings from '@modules/Builder/resources/components/settings/background-settings.vue';
import BorderStyleSettings from '@modules/Builder/resources/components/settings/border-style-settings.vue';
import DecorationSettings from '@modules/Builder/resources/components/settings/decoration-settings.vue';
import DimensionSettings from '@modules/Builder/resources/components/settings/dimension-settings.vue';
import ElementInfoSettings from '@modules/Builder/resources/components/settings/element-info-settings.vue';
import PositionSettings from '@modules/Builder/resources/components/settings/position-settings.vue';
import SpacingSettings from '@modules/Builder/resources/components/settings/spacing-settings.vue';
import TransitionSettings from '@modules/Builder/resources/components/settings/transition-settings.vue';
import Accordion from '@modules/Builder/resources/draggables/components/accordion/accordion.vue';
import AccordionSettings from '@modules/Builder/resources/draggables/components/accordion/components/accordion-settings.vue';
import Render from '@modules/Builder/resources/draggables/components/accordion/render.vue';

export default {
    component: Accordion,
    renderable: Render,
    settings: [
        { name: 'Info', component: ElementInfoSettings },
        { name: 'Items', component: AccordionSettings },
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
