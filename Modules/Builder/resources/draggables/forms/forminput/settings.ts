import ElementInfoSettings from '@modules/Builder/resources/components/settings/element-info-settings.vue';
import FormInputSettings from '@modules/Builder/resources/draggables/forms/forminput/components/form-input-settings.vue';
import FormInput from '@modules/Builder/resources/draggables/forms/forminput/forminput.vue';
import Render from '@modules/Builder/resources/draggables/forms/forminput/render.vue';

export default {
    component: FormInput,
    renderable: Render,
    settings: [
        { name: 'Info', component: ElementInfoSettings },
        { name: 'Input settings', component: FormInputSettings },
    ],
};
