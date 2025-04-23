import './assets/main.css'

import {createApp} from 'vue'
import PrimeVue from 'primevue/config'
import ToastService from 'primevue/toastservice';
import SuperLogisticsTheme from "@/root/theme.js";
import 'primeicons/primeicons.css'
import {loadComponents, components} from "@/root/components.js";

import App from './root/App.vue'
import router from './router'
import store from '@/root/stores/store'

const app = createApp(App)

loadComponents(app, components)
app.use(PrimeVue, {
    theme: {
        preset: SuperLogisticsTheme,
        options: {
            prefix: 'p',
            darkModeSelector: 'system',
            cssLayer: {
                name: 'primevue',
                order: 'tailwind-base, primevue,  tailwind-utilities'
            }
        }
    }
})

app.use(store)
app.use(router)
app.use(ToastService)

app.mount('#super-logistics-app')
