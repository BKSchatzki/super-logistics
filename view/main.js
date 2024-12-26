import './assets/main.css'
import changeTheme from '@/root/theme.js'

import {createApp} from 'vue'
import PrimeVue from 'primevue/config'
import Aura from '@primevue/themes/aura'
import {loadComponents, components} from "@/root/components.js";

import App from './root/App.vue'
import router from './router'
import store from '@/root/stores/store'
import mixin from '@/root/mixin'

const app = createApp(App)

loadComponents(app, components)
app.use(PrimeVue, {
    theme: {
        preset: Aura,
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
app.mixin(mixin)

app.mount('#super-logistics-app')
changeTheme()
