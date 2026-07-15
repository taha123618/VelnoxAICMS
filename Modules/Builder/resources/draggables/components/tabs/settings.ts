import AnimationSettings from '@modules/Builder/resources/components/settings/animation-settings.vue';
import BackgroundSettings from '@modules/Builder/resources/components/settings/background-settings.vue';
import BorderStyleSettings from '@modules/Builder/resources/components/settings/border-style-settings.vue';
import DecorationSettings from '@modules/Builder/resources/components/settings/decoration-settings.vue';
import DimensionSettings from '@modules/Builder/resources/components/settings/dimension-settings.vue';
import ElementInfoSettings from '@modules/Builder/resources/components/settings/element-info-settings.vue';
import PositionSettings from '@modules/Builder/resources/components/settings/position-settings.vue';
import SpacingSettings from '@modules/Builder/resources/components/settings/spacing-settings.vue';
import TransitionSettings from '@modules/Builder/resources/components/settings/transition-settings.vue';
import TabsItemSettings from '@modules/Builder/resources/draggables/components/tabs/components/tabs-items-settings.vue';
import TabsItemsStyleSettings from '@modules/Builder/resources/draggables/components/tabs/components/tabs-items-style-settings.vue';
import Render from '@modules/Builder/resources/draggables/components/tabs/render.vue';
import Tabs from '@modules/Builder/resources/draggables/components/tabs/tabs.vue';

export default {
    component: Tabs,
    renderable: Render,
    settings: [
        { name: 'Info', component: ElementInfoSettings },
        { name: 'Items', component: TabsItemSettings },
        { name: 'Items config', component: TabsItemsStyleSettings },
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
