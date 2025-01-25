import {definePreset} from '@primevue/themes';
import Aura from '@primevue/themes/aura';

const SuperLogisticsTheme = definePreset(Aura, {
    semantic: {
        primary: {
            50: "#DCF2FE",
            100: "#B4E4FE",
            200: "#68C9FD",
            300: "#1DAEFB",
            400: "#0384C9",
            500: "#02527E",
            600: "#024264",
            700: "#01314B",
            800: "#012132",
            900: "#001019",
            950: "#000A0F"
        }
    }
});

export default SuperLogisticsTheme;