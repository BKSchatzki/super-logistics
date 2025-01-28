import {definePreset} from '@primevue/themes';
import Aura from '@primevue/themes/aura';

const SuperLogisticsTheme = definePreset(Aura, {
    semantic: {
        primary: {
            50: "#FFF4E5",
            100: "#FFE9CC",
            200: "#FFD59E",
            300: "#FFBF6B",
            400: "#FFA93B",
            500: "#FF9305",
            600: "#D17600",
            700: "#9E5A00",
            800: "#6B3D00",
            900: "#331D00",
            950: "#190E00"
        }
    }
});

export default SuperLogisticsTheme;