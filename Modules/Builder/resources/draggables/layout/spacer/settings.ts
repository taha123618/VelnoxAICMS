import DimensionSettings from '@modules/Builder/resources/components/settings/dimension-settings.vue';
import ElementInfoSettings from '@modules/Builder/resources/components/settings/element-info-settings.vue';
import SpacingSettings from '@modules/Builder/resources/components/settings/spacing-settings.vue';
import Spacer from './spacer.vue';
import Render from './render.vue';

export default {
    component: Spacer,
    renderable: Render,
    settings: [
        { name: 'Info', component: ElementInfoSettings },
        { name: 'Size', component: DimensionSettings },
        { name: 'Spacing', component: SpacingSettings }
    ]
};
