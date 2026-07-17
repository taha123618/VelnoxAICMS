import BorderStyleSettings from '@modules/Builder/resources/components/settings/border-style-settings.vue';
import DimensionSettings from '@modules/Builder/resources/components/settings/dimension-settings.vue';
import ElementInfoSettings from '@modules/Builder/resources/components/settings/element-info-settings.vue';
import SpacingSettings from '@modules/Builder/resources/components/settings/spacing-settings.vue';
import Divider from './divider.vue';
import Render from './render.vue';

export default {
    component: Divider,
    renderable: Render,
    settings: [
        { name: 'Info', component: ElementInfoSettings },
        { name: 'Border/Line style', component: BorderStyleSettings },
        { name: 'Spacing', component: SpacingSettings },
        { name: 'Size', component: DimensionSettings }
    ]
};
