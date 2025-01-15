import {definePreset} from '@primevue/themes';
import Aura from '@primevue/themes/aura';

const SuperLogisticsTheme = definePreset(Aura, {
    semantic: {
        primary: {
            50: '#f9d7dd',
            100: '#f2abb8',
            200: '#e65b75',
            300: '#c41e3c',
            400: '#a81a34',
            500: '#8e162c',
            600: '#6f1122',
            700: '#540d1a',
            800: '#3a0912',
            900: '#1b0408',
            950: '#0d0204',
        }
    }
});

export default SuperLogisticsTheme;